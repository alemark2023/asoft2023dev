<?php

namespace Modules\Purchase\Http\Controllers\Api;

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
use Modules\Item\Models\ItemLotsGroup;


class PurchaseController extends Controller
{

    public function records()
    {
        $records = Purchase::latest()->get();

        return new PurchaseCollection($records);
    }

    public function tables()
    {
        $document_types_invoice = DocumentType::whereIn('id', ['01', '03', 'GU75', 'NE76'])->get();

        return compact('document_types_invoice');
    }

    public function suppliers()
    {
        return $this->table('suppliers');
    }

    public function searchSuppliers(Request $request)
    {

        $identity_document_type_id = $this->getIdentityDocumentTypeId($request->document_type_id);
        
        $persons = Person::where('number','like', "%{$request->input}%")
                            ->orWhere('name','like', "%{$request->input}%")
                            ->whereType('suppliers')
                            ->whereIn('identity_document_type_id', $identity_document_type_id)
                            ->orderBy('name')
                            ->get()
                            ->transform(function($row) {
                                return [
                                    'id' => $row->id,
                                    'description' => $row->number.' - '.$row->name,
                                    'name' => $row->name,
                                    'number' => $row->number,
                                    'identity_document_type_id' => $row->identity_document_type_id,
                                    'address' => $row->address,
                                    'email' => $row->email,
                                    'selected' => false
                                ];
                            });

        return $persons;

    }

    
    public function getIdentityDocumentTypeId($document_type_id){

        return ($document_type_id == '01') ? [6] : [1,4,6,7,0];
        
    }


    public function item_tables()
    {

        $items = $this->table('items');
        $affectation_igv_types = AffectationIgvType::whereActive()->get();

        return compact('items', 'affectation_igv_types');
    }


    public function record($id)
    {
        $record = new PurchaseResource(Purchase::findOrFail($id));
        return $record;
    }

    public function store(PurchaseRequest $request)
    {

        $data = self::convert($request);

        $purchase = DB::connection('tenant')->transaction(function () use ($data) {

            $doc = Purchase::create($data);

            foreach ($data['items'] as $row)
            {
                $doc->items()->create($row);
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


    public static function convert($inputs)
    {
        $company = Company::active();
        $values = [
            'user_id' => auth()->id(),
            'establishment_id' => auth()->user()->establishment_id,
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

                $suppliers = Person::whereType('suppliers')->orderBy('name')->take(20)->get()->transform(function($row) {
                    return [
                        'id' => $row->id,
                        'description' => $row->number.' - '.$row->name,
                        'name' => $row->name,
                        'number' => $row->number,
                        'identity_document_type_id' => $row->identity_document_type_id,
                        'address' => $row->address,
                        'email' => $row->email,
                        'selected' => false
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
                    ];
                });

                break;
            default:

                return [];

                break;
        }
    }


}
