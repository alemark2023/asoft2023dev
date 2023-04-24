<?php

namespace App\Http\Controllers\Tenant\Api;

use App\Http\Controllers\Tenant\EmailController;
use Exception;
use Carbon\Carbon;
use App\Models\Tenant\Item;
use App\Models\Tenant\User;
use Illuminate\Http\Request;
use App\Models\Tenant\Person;
use App\Models\Tenant\Series;
use App\Models\Tenant\Company;
use App\Models\Tenant\PaymentMethodType;
use App\Models\Tenant\Document;
use App\Mail\Tenant\DocumentEmail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Configuration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Tenant\PersonRequest;
use Modules\Item\Http\Requests\ItemRequest;
use Modules\Dashboard\Helpers\DashboardData;
use Modules\Finance\Helpers\UploadFileHelper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Item\Http\Requests\ItemUpdateRequest;
use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Warehouse;
use Modules\Inventory\Models\ItemWarehouse;
use Modules\Finance\Traits\FinanceTrait;
use Modules\MobileApp\Models\AppConfiguration;
use Modules\Item\Models\{
    Category
};
use App\Http\Controllers\Tenant\ItemController as ItemWebController;


class MobileController extends Controller
{
    use  FinanceTrait;

    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return [
                'success' => false,
                'message' => 'No Autorizado'
            ];
        }

        $company = Company::active();

        $user = $request->user();
        return [
            'success' => true,
            'name' => $user->name,
            'email' => $user->email,
            'seriedefault' => $user->series_id,
            'token' => $user->api_token,
            'restaurant_role_id' => $user->restaurant_role_id,
            'restaurant_role_code' => $user->restaurant_role_id ? $user->restaurant_role->code : null,
            'ruc' => $company->number,
            'app_logo' => $company->app_logo,
            'app_logo_base64' => '',//base64_encode(file_get_contents(config('app.url').'/storage/uploads/logos/'.$company->app_logo)),
            'company' => [
                'name' => $company->name,
                'address' => auth()->user()->establishment->department->description.', '.auth()->user()->establishment->province->description.', '.auth()->user()->establishment->district->description.', '.auth()->user()->establishment->address,
                'phone' => auth()->user()->establishment->telephone,
                'email' => auth()->user()->establishment->email
            ],
            'app_configuration' => $this->getAppConfiguration(),
        ];

    }


    /**
     *
     * Obtener configuracion para app
     *
     * @return array
     */
    public function getAppConfiguration()
    {
        return optional(AppConfiguration::first())->getRowResource();
    }


    public function customers()
    {
        $customers = Person::whereType('customers')->orderBy('name')->take(20)->get()->transform(function($row) {
            return [
                'id' => $row->id,
                'description' => $row->number.' - '.$row->name,
                'name' => $row->name,
                'number' => $row->number,
                'identity_document_type_id' => $row->identity_document_type_id,
                'identity_document_type_code' => $row->identity_document_type->code,
                'address' => $row->address,
                'telephone' => $row->telephone,
                'country_id' => $row->country_id,
                'district_id' => $row->district_id,
                'email' => $row->email,
                'selected' => false
            ];
        });

        return [
            'success' => true,
            'data' => array('customers' => $customers)
        ];

    }

    public function tables()
    {
        $affectation_igv_types = AffectationIgvType::whereActive()->get();
        /*$customers = Person::whereType('customers')->orderBy('name')->take(20)->get()->transform(function($row) {
            return [
                'id' => $row->id,
                'description' => $row->number.' - '.$row->name,
                'name' => $row->name,
                'number' => $row->number,
                'identity_document_type_id' => $row->identity_document_type_id,
                'identity_document_type_code' => $row->identity_document_type->code
            ];
        });*/
        $establishment_id = auth()->user()->establishment_id;
        $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();

        $items = Item::with(['brand', 'category'])
                    ->whereWarehouse()
                    ->whereHasInternalId()
                    // ->whereNotIsSet()
                    ->whereIsActive()
                    ->orderBy('description')
                    ->take(20)
                    ->get()
                    ->transform(function($row) use($warehouse){
                        $full_description = ($row->internal_id)?$row->internal_id.' - '.$row->description:$row->description;

                        return [
                            'id' => $row->id,
                            'item_id' => $row->id,
                            'name' => $row->name,
                            'full_description' => $full_description,
                            'description' => $row->description,
                            'currency_type_id' => $row->currency_type_id,
                            'internal_id' => $row->internal_id,
                            'item_code' => $row->item_code,
                            'currency_type_symbol' => $row->currency_type->symbol,
                            'sale_unit_price' => $row->generalApplyNumberFormat($row->sale_unit_price),
                            // 'sale_unit_price' => number_format($row->sale_unit_price, 2),
                            'price' => $row->sale_unit_price,
                            'purchase_unit_price' => $row->purchase_unit_price,
                            'unit_type_id' => $row->unit_type_id,
                            'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                            'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id,
                            'calculate_quantity' => (bool) $row->calculate_quantity,
                            'has_igv' => (bool) $row->has_igv,
                            'is_set' => (bool) $row->is_set,
                            'aux_quantity' => 1,
                            'brand' => $row->brand->name,
                            'category' => $row->brand->name,
                            'stock' => $row->getWarehouseCurrentStock($warehouse),
                            // 'stock' => $row->unit_type_id!='ZZ' ? ItemWarehouse::where([['item_id', $row->id],['warehouse_id', $warehouse->id]])->first()->stock : '0',
                            'image' => $row->image != "imagen-no-disponible.jpg" ? url("/storage/uploads/items/" . $row->image) : url("/logo/" . $row->image),
                        ];
                    });


        return [
            'success' => true,
            'data' => [
                'items' => $items,
                'affectation_types' => $affectation_igv_types,
                'categories' => Category::filterForTables()->get()
            ]
        ];

    }


    public function getSeries()
    {

        return Series::where('establishment_id', auth()->user()->establishment_id)
                    ->whereIn('document_type_id', ['01', '03'])
                    ->get()
                    ->transform(function($row) {
                        return $row->getApiRowResource();
                    });

    }

    public function getPaymentmethod(){

        $payment_method_type = PaymentMethodType::all();
        $payment_destinations = $this->getPaymentDestinations();
        return compact( 'payment_method_type','payment_destinations');
    }


    public function document_email(Request $request)
    {
        $company = Company::active();
        $document = Document::find($request->id);
        $customer_email = $request->email;

        $email = $customer_email;
        $mailable =new DocumentEmail($company, $document);
        $id =  $request->id;
        $sendIt = EmailController::SendMail($email, $mailable, $id, 1);
        /*
        Configuration::setConfigSmtpMail();
        $array_email = explode(',', $customer_email);
        if (count($array_email) > 1) {
            foreach ($array_email as $email_to) {
                $email_to = trim($email_to);
                if(!empty($email_to)) {
                    Mail::to($email_to)->send(new DocumentEmail($company, $document));
                }
            }
        } else {
            Mail::to($customer_email)->send(new DocumentEmail($company, $document));
        }
        */

        return [
            'success' => true,
            'message'=> 'Email enviado correctamente.'
        ];
    }


    public function item(ItemRequest $request)
    {

        $row = DB::connection('tenant')->transaction(function () use ($request) {

            $row = new Item();
            $row->item_type_id = '01';
            $row->amount_plastic_bag_taxes = Configuration::firstOrFail()->amount_plastic_bag_taxes;
            $row->fill($request->all());
            $temp_path = $request->input('temp_path');

            if($temp_path) {

                UploadFileHelper::checkIfValidFile($request->input('image'), $temp_path, true);

                $directory = 'public'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'items'.DIRECTORY_SEPARATOR;

                $file_name_old = $request->input('image');
                $file_name_old_array = explode('.', $file_name_old);
                $file_content = file_get_contents($temp_path);
                $datenow = date('YmdHis');
                $file_name = Str::slug($row->description).'-'.$datenow.'.'.$file_name_old_array[1];
                Storage::put($directory.$file_name, $file_content);
                $row->image = $file_name;

                //--- IMAGE SIZE MEDIUM
                $image = \Image::make($temp_path);
                $file_name = Str::slug($row->description).'-'.$datenow.'_medium'.'.'.$file_name_old_array[1];
                $image->resize(512, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                Storage::put($directory.$file_name,  (string) $image->encode('jpg', 30));
                $row->image_medium = $file_name;

                //--- IMAGE SIZE SMALL
                $image = \Image::make($temp_path);
                $file_name = Str::slug($row->description).'-'.$datenow.'_small'.'.'.$file_name_old_array[1];
                $image->resize(256, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                Storage::put($directory.$file_name,  (string) $image->encode('jpg', 20));
                $row->image_small = $file_name;



            }else if(!$request->input('image') && !$request->input('temp_path') && !$request->input('image_url')){
                $row->image = 'imagen-no-disponible.jpg';
            }

            $row->save();

            (new ItemWebController)->generateInternalId($row);

            return $row;

        });

        $full_description = ($row->internal_id)?$row->internal_id.' - '.$row->description:$row->description;

        return [
            'success' => true,
            'msg' => 'Producto registrado con éxito',
            'data' => (object)[
                'id' => $row->id,
                'item_id' => $row->id,
                'name' => $row->name,
                'full_description' => $full_description,
                'description' => $row->description,
                'currency_type_id' => $row->currency_type_id,
                'internal_id' => $row->internal_id,
                'item_code' => $row->item_code,
                'barcode' => $row->barcode,
                'currency_type_symbol' => $row->currency_type->symbol,
                'sale_unit_price' => number_format( $row->sale_unit_price, 2),
                'purchase_unit_price' => $row->purchase_unit_price,
                'unit_type_id' => $row->unit_type_id,
                'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id,
                'calculate_quantity' => (bool) $row->calculate_quantity,
                'has_igv' => (bool) $row->has_igv,
                'is_set' => (bool) $row->is_set,
                'aux_quantity' => 1,
            ],
        ];

    }

    public function person(PersonRequest $request)
    {
        $row = new Person();
        if ($request->department_id === '-') {
            $request->merge([
                'department_id' => null,
                'province_id'   => null,
                'district_id'   => null
            ]);
        }
        $row->fill($request->all());
        $row->save();

        return [
            'success' => true,
            'msg' => ($request->type == 'customers') ? 'Cliente registrado con éxito' : 'Proveedor registrado con éxito',
            'data' => (object)[
                'id' => $row->id,
                'description' => $row->number.' - '.$row->name,
                'name' => $row->name,
                'number' => $row->number,
                'identity_document_type_id' => $row->identity_document_type_id,
                'identity_document_type_code' => $row->identity_document_type->code,
                'address' => $row->address,
                'email' => $row->email,
                'telephone' => $row->telephone,
                'country_id' => $row->country_id,
                'district_id' => $row->district_id,
                'selected' => false
            ]
        ];
    }

    public function searchItems(Request $request)
    {
        $establishment_id = auth()->user()->establishment_id;
        $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();
        $search_by_barcode = $request->has('search_by_barcode') && (bool) $request->search_by_barcode;
        $category_id = $request->category_id ?? null;
        $limit = $request->limit ?? null;

        $item_query = Item::query();

        if($search_by_barcode)
        {
            $item_query->where('barcode', $request->input)->limit(1);
        }
        else
        {
            $item_query->where('description', 'like', "%{$request->input}%")->orWhere('internal_id', 'like', "%{$request->input}%");

            if($limit) $item_query->limit($limit);
        }

        $items = $item_query->whereHasInternalId()
                    ->whereWarehouse()
                    // ->whereNotIsSet()
                    ->filterByCategory($category_id)
                    ->whereIsActive()
                    ->orderBy('description')
                    ->get()
                    ->transform(function($row) use($warehouse){

                        $full_description = ($row->internal_id)?$row->internal_id.' - '.$row->description:$row->description;

                        return [
                            'id' => $row->id,
                            'item_id' => $row->id,
                            'name' => $row->name,
                            'full_description' => $full_description,
                            'description' => $row->description,
                            'currency_type_id' => $row->currency_type_id,
                            'internal_id' => $row->internal_id,
                            'item_code' => $row->item_code ?? '',
                            'currency_type_symbol' => $row->currency_type->symbol,
                            'sale_unit_price' => $row->generalApplyNumberFormat($row->sale_unit_price),
                            // 'sale_unit_price' => number_format( $row->sale_unit_price, 2),
                            'purchase_unit_price' => $row->purchase_unit_price,
                            'unit_type_id' => $row->unit_type_id,
                            'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                            'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id,
                            'calculate_quantity' => (bool) $row->calculate_quantity,
                            'has_igv' => (bool) $row->has_igv,
                            'is_set' => (bool) $row->is_set,
                            'aux_quantity' => 1,
                            'barcode' => $row->barcode ?? '',
                            'brand_id' => $row->brand_id,
                            'brand' => optional($row->brand)->name,
                            'category_id' => $row->category_id,
                            'category' => optional($row->category)->name,
                            'stock' => $row->getWarehouseCurrentStock($warehouse),
                            // 'stock' => $row->unit_type_id!='ZZ' ? ItemWarehouse::where([['item_id', $row->id],['warehouse_id', $warehouse->id]])->first()->stock : '0',
                            'image' => $row->image != "imagen-no-disponible.jpg" ? url("/storage/uploads/items/" . $row->image) : url("/logo/" . $row->image),
                            'warehouses' => collect($row->warehouses)->transform(function($row) {
                                return [
                                    'warehouse_description' => $row->warehouse->description,
                                    'stock' => $row->stock,
                                    'warehouse_id' => $row->warehouse_id,
                                ];
                            }),
                            'item_unit_types' => $row->item_unit_types->transform(function($row) {
                                return [
                                    'id' => $row->id,
                                    'description' => $row->description,
                                    'unit_type_id' => $row->unit_type_id,
                                    'quantity_unit' => $row->quantity_unit,
                                    'price1' => $row->price1,
                                    'price2' => $row->price2,
                                    'price3' => $row->price3,
                                    'price_default' => $row->price_default,
                                ];
                            }),
                            'has_isc' => (bool)$row->has_isc,
                            'system_isc_type_id' => $row->system_isc_type_id,
                            'percentage_isc' => $row->percentage_isc,
                        ];
                    });

        return [
            'success' => true,
            'data' => array('items' => $items)
        ];
    }

    public function searchCustomers(Request $request)
    {

        $identity_document_type_id = $this->getIdentityDocumentTypeId($request->document_type_id);

        $customers = Person::where('name', 'like', "%{$request->input}%" )
                            ->orWhere('number','like', "%{$request->input}%")
                            ->whereType('customers')
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
                                    'identity_document_type_code' => $row->identity_document_type->code,
                                    'address' => $row->address,
                                    'telephone' => $row->telephone,
                                    'email' => $row->email,
                                    'country_id' => $row->country_id,
                                    'district_id' => $row->district_id,
                                    'selected' => false
                                ];
                            });

        return [
            'success' => true,
            'data' => array('customers' => $customers)
        ];
    }


    public function getIdentityDocumentTypeId($document_type_id){

        return ($document_type_id == '01') ? [6] : [1,4,6,7,0];

    }

    public function report()
    {
        $request = [
            'customer_id' => null,
            'date_end' => date('Y-m-d'),
            'date_start' => date('Y-m-d'),
            'enabled_expense' => null,
            'enabled_move_item' => false,
            'enabled_transaction_customer' => false,
            'establishment_id' => 1,
            'item_id' => null,
            'month_end' => date('Y-m'),
            'month_start' => date('Y-m'),
            'period' => 'month',
        ];

        return [
            'data' => (new DashboardData())->data_mobile($request)
        ];
    }

    public function updateItem(ItemUpdateRequest $request, $itemId)
    {
        $row = Item::findOrFail($itemId);

        $row->fill($request->only('internal_id', 'barcode', 'model', 'has_igv', 'description', 'sale_unit_price', 'stock_min', 'item_code'));
        $row->save();

        $full_description = ($row->internal_id)?$row->internal_id.' - '.$row->description:$row->description;

        return [
            'success' => true,
            'msg' => 'Producto editado con éxito',
            'data' => (object)[
                'id' => $row->id,
                'item_id' => $row->id,
                'name' => $row->name,
                'full_description' => $full_description,
                'description' => $row->description,
                'currency_type_id' => $row->currency_type_id,
                'internal_id' => $row->internal_id,
                'item_code' => $row->item_code,
                'currency_type_symbol' => $row->currency_type->symbol,
                'sale_unit_price' => number_format( $row->sale_unit_price, 2),
                'purchase_unit_price' => $row->purchase_unit_price,
                'unit_type_id' => $row->unit_type_id,
                'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id,
                'calculate_quantity' => (bool) $row->calculate_quantity,
                'has_igv' => (bool) $row->has_igv,
                'is_set' => (bool) $row->is_set,
                'aux_quantity' => 1,
            ],
        ];
    }

    //subir imagen app
    public function upload(Request $request)
    {

        $validate_upload = UploadFileHelper::validateUploadFile($request, 'file', 'jpg,jpeg,png,gif,svg');

        if(!$validate_upload['success']){
            return $validate_upload;
        }

        if ($request->hasFile('file')) {
            $new_request = [
                'file' => $request->file('file'),
                'type' => $request->input('type'),
            ];

            return $this->upload_image($new_request);
        }
        return [
            'success' => false,
            'message' =>  __('app.actions.upload.error'),
        ];
    }


    function upload_image($request)
    {
        $file = $request['file'];
        $type = $request['type'];

        $temp = tempnam(sys_get_temp_dir(), $type);
        file_put_contents($temp, file_get_contents($file));

        $mime = mime_content_type($temp);
        $data = file_get_contents($temp);

        return [
            'success' => true,
            'data' => [
                'filename' => $file->getClientOriginalName(),
                'temp_path' => $temp,
                'temp_image' => 'data:' . $mime . ';base64,' . base64_encode($data)
            ]
        ];
    }


}

