<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Person;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\ChargeDiscountType;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\PurchaseItem;
use Modules\Purchase\Models\PurchaseOrder;

use App\CoreFacturalo\Requests\Inputs\Common\LegendInput;
use App\Models\Tenant\Item;
use App\Http\Resources\Tenant\PurchaseCollection;
use App\Http\Resources\Tenant\PurchaseResource;
use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\DocumentType;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\Catalogs\PriceType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\Catalogs\AttributeType;
use App\Models\Tenant\Company;
use App\Http\Requests\Tenant\PurchaseRequest;
use App\Http\Requests\Tenant\PurchaseImportRequest;

use Illuminate\Support\Str;
use App\CoreFacturalo\Requests\Inputs\Common\PersonInput;
use App\Models\Tenant\PaymentMethodType;
use Carbon\Carbon;
use Modules\Inventory\Models\Warehouse;
use App\Models\Tenant\InventoryKardex;
use App\Models\Tenant\ItemWarehouse;
use Modules\Finance\Traits\FinanceTrait;
use Modules\Item\Models\ItemLotsGroup;


class PurchaseController extends Controller
{

    use FinanceTrait;

    public function index()
    {
        return view('tenant.purchases.index');
    }


    public function create($purchase_order_id = null)
    {
        return view('tenant.purchases.form', compact('purchase_order_id'));
    }

    public function columns()
    {
        return [
            'number' => 'Número',
            'date_of_issue' => 'Fecha de emisión',
            'date_of_due' => 'Fecha de vencimiento',
            'date_of_payment' => 'Fecha de pago',
            'name' => 'Nombre proveedor',
        ];
    }

    public function records(Request $request)
    {

        $records = $this->getRecords($request);

        return new PurchaseCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function getRecords($request){

        switch ($request->column) {
            case 'name':

                $records = Purchase::whereHas('supplier', function($query) use($request){
                                return $query->where($request->column, 'like', "%{$request->value}%");
                            })
                            ->whereTypeUser()
                            ->latest();

                break;

            case 'date_of_payment':

                $records = Purchase::whereHas('purchase_payments', function($query) use($request){
                                return $query->where($request->column, 'like', "%{$request->value}%");
                            })
                            ->whereTypeUser()
                            ->latest();

                break;

            default:

                $records = Purchase::where($request->column, 'like', "%{$request->value}%")
                            ->whereTypeUser()
                            ->latest();

                break;
        }

        return $records;

    }

    public function tables()
    {
        $suppliers = $this->table('suppliers');
        $establishment = Establishment::where('id', auth()->user()->establishment_id)->first();
        $currency_types = CurrencyType::whereActive()->get();
        $document_types_invoice = DocumentType::whereIn('id', ['01', '03', 'GU75', 'NE76'])->get();
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $company = Company::active();
        $payment_method_types = PaymentMethodType::all();
        $payment_destinations = $this->getPaymentDestinations();
        $customers = $this->getPersons('customers');

        return compact('suppliers', 'establishment','currency_types', 'discount_types',
                    'charge_types', 'document_types_invoice','company','payment_method_types', 'payment_destinations', 'customers');
    }

    public function item_tables()
    {

        $items = $this->table('items');
        $categories = [];
        $affectation_igv_types = AffectationIgvType::whereActive()->get();
        $system_isc_types = SystemIscType::whereActive()->get();
        $price_types = PriceType::whereActive()->get();
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $attribute_types = AttributeType::whereActive()->orderByDescription()->get();
        $warehouses = Warehouse::all();

        return compact('items', 'categories', 'affectation_igv_types', 'system_isc_types', 'price_types',
                        'discount_types', 'charge_types', 'attribute_types','warehouses');
    }

    public function record($id)
    {

        $record = new PurchaseResource(Purchase::findOrFail($id));

        return $record;
    }

    public function edit($id)
    {
        $resourceId = $id;
        return view('tenant.purchases.form_edit', compact('resourceId'));
    }

    public function store(PurchaseRequest $request)
    {

        //return 'asd';
        $data = self::convert($request);

        $purchase = DB::connection('tenant')->transaction(function () use ($data) {
            $doc = Purchase::create($data);
            foreach ($data['items'] as $row)
            {
                // $doc->items()->create($row);
                $p_item = new PurchaseItem;
                $p_item->fill($row);
                $p_item->purchase_id = $doc->id;
                $p_item->save();

                if(array_key_exists('lots', $row)){

                    foreach ($row['lots'] as $lot){

                        $p_item->lots()->create([
                            'date' => $lot['date'],
                            'series' => $lot['series'],
                            'item_id' => $row['item_id'],
                            'warehouse_id' => $row['warehouse_id'],
                            'has_sale' => false,
                            'state' => $lot['state']
                        ]);

                    }

                }

                if(array_key_exists('item', $row))
                {
                    if( $row['item']['lots_enabled'] == true)
                    {

                        ItemLotsGroup::create([
                            'code'  => $row['lot_code'],
                            'quantity'  => $row['quantity'],
                            'date_of_due'  => $row['date_of_due'],
                            'item_id' => $row['item_id']
                        ]);

                    }
                }

            }


            foreach ($data['payments'] as $payment) {

                $record_payment = $doc->purchase_payments()->create($payment);

                if(isset($payment['payment_destination_id'])){
                    $this->createGlobalPayment($record_payment, $payment);
                }
            }

            return $doc;
        });



        return [
            'success' => true,
            'data' => [
                'id' => $purchase->id,
                'number_full' => "{$purchase->series}-{$purchase->number}",
            ],
        ];
    }

    public function update(PurchaseRequest $request)
    {



        $purchase = DB::connection('tenant')->transaction(function () use ($request) {

            $doc = Purchase::firstOrNew(['id' => $request['id']]);
           // return json_encode($doc);
            $doc->fill($request->all());
            $doc->save();

            $establishment = Establishment::where('id', auth()->user()->establishment_id)->first();
            //proceso para eliminar los actualizar el stock de proiductos
            foreach ($doc->items as $item) {
                $item->purchase->inventory_kardex()->create([
                    'date_of_issue' => date('Y-m-d'),
                    'item_id' => $item->item_id,
                    'warehouse_id' => $establishment->id,
                    'quantity' => -$item->quantity,
                ]);
                $wr = ItemWarehouse::where([['item_id', $item->item_id],['warehouse_id', $establishment->id]])->first();
                $wr->stock =  $wr->stock - $item->quantity;
                $wr->save();
            }

            foreach ($doc->items()->get() as $it) {
                // dd($it);
                $it->lots()->delete();
            }


            $doc->items()->delete();

            foreach ($request['items'] as $row)
            {
                // $doc->items()->create($row);
                $p_item = new PurchaseItem;
                $p_item->fill($row);
                $p_item->purchase_id = $doc->id;
                $p_item->save();

                if(array_key_exists('lots', $row)){

                    foreach ($row['lots'] as $lot){

                        $p_item->lots()->create([
                            'date' => $lot['date'],
                            'series' => $lot['series'],
                            'item_id' => $row['item_id'],
                            'warehouse_id' => $row['warehouse_id'],
                            'has_sale' => false
                        ]);

                    }
                }
            }

            // $doc->purchase_payments()->delete();
            $this->deleteAllPayments($doc->purchase_payments);

            foreach ($request['payments'] as $payment) {

                $record_payment = $doc->purchase_payments()->create($payment);

                if(isset($payment['payment_destination_id'])){
                    $this->createGlobalPayment($record_payment, $payment);
                }
                
                if(isset($payment['payment_filename'])){
                    $record_payment->payment_file()->create([
                        'filename' => $payment['payment_filename']
                    ]);
                }
            }

            return $doc;
        });

        return [
            'success' => true,
            'data' => [
                'id' => $purchase->id,
            ],
        ];



    }

    public function anular($id)
    {
        $obj =  Purchase::find($id);
        $obj->state_type_id = 11;
        $obj->save();

        $establishment = Establishment::where('id', auth()->user()->establishment_id)->first();
        $warehouse = Warehouse::where('establishment_id',$establishment->id)->first();

        //proceso para eliminar los actualizar el stock de proiductos
        foreach ($obj->items as $item) {
            $item->purchase->inventory_kardex()->create([
                'date_of_issue' => date('Y-m-d'),
                'item_id' => $item->item_id,
                'warehouse_id' => $establishment->id,
                'quantity' => -$item->quantity,
            ]);
            $wr = ItemWarehouse::where([['item_id', $item->item_id],['warehouse_id', $warehouse->id]])->first();
            $wr->stock =  $wr->stock - $item->quantity;
            $wr->save();
        }

        return [
            'success' => true,
            'message' => 'Compra anulada con éxito'
        ];
    }

    public static function convert($inputs)
    {
        $company = Company::active();
        $values = [
            'user_id' => auth()->id(),
            'external_id' => Str::uuid()->toString(),
            'supplier' => PersonInput::set($inputs['supplier_id']),
            'soap_type_id' => $company->soap_type_id,
            'group_id' => ($inputs->document_type_id === '01') ? '01':'02',
            'state_type_id' => '01'
        ];

        $inputs->merge($values);

        return $inputs->all();
    }

    public function table($table)
    {
        switch ($table) {
            case 'suppliers':

                $suppliers = Person::whereType('suppliers')->orderBy('name')->get()->transform(function($row) {
                    return [
                        'id' => $row->id,
                        'description' => $row->number.' - '.$row->name,
                        'name' => $row->name,
                        'number' => $row->number,
                        'perception_agent' => (bool) $row->perception_agent,
                        'identity_document_type_id' => $row->identity_document_type_id,
                        'identity_document_type_code' => $row->identity_document_type->code
                    ];
                });
                return $suppliers;

                break;

            case 'items':

                $items = Item::whereNotIsSet()->whereIsActive()->orderBy('description')->get(); //whereWarehouse()
                return collect($items)->transform(function($row) {
                    $full_description = ($row->internal_id)?$row->internal_id.' - '.$row->description:$row->description;
                    return [
                        'id' => $row->id,
                        'item_code'  => $row->item_code,
                        'full_description' => $full_description,
                        'description' => $row->description,
                        'currency_type_id' => $row->currency_type_id,
                        'currency_type_symbol' => $row->currency_type->symbol,
                        'sale_unit_price' => $row->sale_unit_price,
                        'purchase_unit_price' => $row->purchase_unit_price,
                        'unit_type_id' => $row->unit_type_id,
                        'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                        'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id,
                        'has_perception' => (bool) $row->has_perception,
                        'lots_enabled' => (bool) $row->lots_enabled,
                        'percentage_perception' => $row->percentage_perception,
                        'item_unit_types' => collect($row->item_unit_types)->transform(function($row) {
                            return [
                                'id' => $row->id,
                                'description' => "{$row->description}",
                                'item_id' => $row->item_id,
                                'unit_type_id' => $row->unit_type_id,
                                'quantity_unit' => $row->quantity_unit,
                                'price1' => $row->price1,
                                'price2' => $row->price2,
                                'price3' => $row->price3,
                                'price_default' => $row->price_default,
                            ];
                        }),
                        'series_enabled' => (bool) $row->series_enabled,

                        // 'warehouses' => collect($row->warehouses)->transform(function($row) {
                        //     return [
                        //         'warehouse_id' => $row->warehouse->id,
                        //         'warehouse_description' => $row->warehouse->description,
                        //         'stock' => $row->stock,
                        //     ];
                        // })
                    ];
                });
//                return $items;

                break;
            default:

                return [];

                break;
        }
    }

    public function delete($id)
    {

        try {

            DB::connection('tenant')->transaction(function () use ($id) {

                $row = Purchase::findOrFail($id);
                $this->deleteAllPayments($row->purchase_payments);
                $row->delete();

            });

            return [
                'success' => true,
                'message' => 'Compra eliminada con éxito'
            ];

        } catch (Exception $e) {

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }



    public function xml2array ( $xmlObject, $out = array () )
    {
        foreach ((array) $xmlObject as $index => $node) {
            $out[$index] = ( is_object ( $node ) ) ?  $this->xml2array($node) : $node;
        }
        return $out;
    }

    function XMLtoArray($xml) {
        $previous_value = libxml_use_internal_errors(true);
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->loadXml($xml);
        libxml_use_internal_errors($previous_value);
        if (libxml_get_errors()) {
            return [];
        }
        return $this->DOMtoArray($dom);
    }

    public function DOMtoArray($root) {
        $result = array();

        if ($root->hasAttributes()) {
            $attrs = $root->attributes;
            foreach ($attrs as $attr) {
                $result['@attributes'][$attr->name] = $attr->value;
            }
        }

        if ($root->hasChildNodes()) {
            $children = $root->childNodes;
            if ($children->length == 1) {
                $child = $children->item(0);
                if (in_array($child->nodeType,[XML_TEXT_NODE,XML_CDATA_SECTION_NODE])) {
                    $result['_value'] = $child->nodeValue;
                    return count($result) == 1
                        ? $result['_value']
                        : $result;
                }

            }
            $groups = array();
            foreach ($children as $child) {
                if (!isset($result[$child->nodeName])) {
                    $result[$child->nodeName] = $this->DOMtoArray($child);
                } else {
                    if (!isset($groups[$child->nodeName])) {
                        $result[$child->nodeName] = array($result[$child->nodeName]);
                        $groups[$child->nodeName] = 1;
                    }
                    $result[$child->nodeName][] = $this->DOMtoArray($child);
                }
            }
        }
        return $result;
    }

    public function import(PurchaseImportRequest $request)
    {
        try
        {
            $model = $request->all();
            $supplier =  Person::whereType('suppliers')->where('number', $model['supplier_ruc'])->first();
            if(!$supplier)
            {
                return [
                    'success' => false,
                    'data' => 'Supplier not exist.'
                ];
            }
            $model['supplier_id'] = $supplier->id;
            $company = Company::active();
            $values = [
                'user_id' => auth()->id(),
                'external_id' => Str::uuid()->toString(),
                'supplier' => PersonInput::set($model['supplier_id']),
                'soap_type_id' => $company['soap_type_id'],
                'group_id' => ($model['document_type_id'] === '01') ? '01':'02',
                'state_type_id' => '01'
            ];

            $data = array_merge($model, $values);

            $purchase = DB::connection('tenant')->transaction(function () use ($data) {
                $doc = Purchase::create($data);
                foreach ($data['items'] as $row)
                {
                    $doc->items()->create($row);
                }

                $doc->purchase_payments()->create([
                    'date_of_payment' => $data['date_of_issue'],
                    'payment_method_type_id' => $data['payment_method_type_id'],
                    'payment' => $data['total'],
                ]);

                return $doc;
            });

            return [
                'success' => true,
                'message' => 'Xml cargado correctamente.',
                'data' => [
                    'id' => $purchase->id,
                ],
            ];



        }catch(Exception $e)
        {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

    }


    public function getPersons($type){

        $persons = Person::whereType($type)->orderBy('name')->take(20)->get()->transform(function($row) {
            return [
                'id' => $row->id,
                'description' => $row->number.' - '.$row->name,
                'name' => $row->name,
                'number' => $row->number,
                'identity_document_type_id' => $row->identity_document_type_id,
            ];
        });

        return $persons;

    }
 

}
