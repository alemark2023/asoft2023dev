<?php
namespace App\Http\Controllers\Tenant;

use App\CoreFacturalo\Facturalo;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\CoreFacturalo\WS\Zip\ZipFly;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\DocumentEmailRequest;
use App\Http\Requests\Tenant\DocumentRequest;
use App\Http\Requests\Tenant\DocumentVoidedRequest;
use App\Http\Resources\Tenant\DocumentCollection;
use App\Http\Resources\Tenant\DocumentResource;
use App\Mail\Tenant\DocumentEmail;
use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\ChargeDiscountType;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Catalogs\NoteCreditType;
use App\Models\Tenant\Catalogs\NoteDebitType;
use App\Models\Tenant\Catalogs\OperationType;
use App\Models\Tenant\Catalogs\PriceType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\Catalogs\AttributeType;
use App\Models\Tenant\Company;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Document;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Item;
use App\Models\Tenant\Person;
use App\Models\Tenant\Series;
use App\Models\Tenant\Warehouse;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Nexmo\Account\Price;
use Illuminate\Support\Facades\Cache;

class DocumentController extends Controller
{
    use StorageDocument;

    public function __construct()
    {
        $this->middleware('input.request:document,web', ['only' => ['store']]);
    }

    public function index()
    {
        $is_client = config('tenant.is_client');

        return view('tenant.documents.index', compact('is_client'));
    }

    public function columns()
    {
        return [
            'number' => 'Número',
            'date_of_issue' => 'Fecha de emisión'
        ];
    }

    public function records(Request $request)
    {
//        $series = Series::select('number')->where('contingency', false)->get();
        $series = Series::select('number')->get();

        $records = Document::where($request->column, 'like', "%{$request->value}%")
                            ->whereIn('series',$series)
                            ->whereTypeUser()
                            ->latest();

        return new DocumentCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function searchCustomers(Request $request)
    {

        //tru de boletas en env esta en true filtra a los con dni   , false a todos
        $identity_document_type_id = $this->getIdentityDocumentTypeId($request->document_type_id);     
         
        $customers = Person::where('number','like', "%{$request->input}%")
                            ->orWhere('name','like', "%{$request->input}%")
                            ->whereType('customers')->orderBy('name')
                            ->whereIn('identity_document_type_id',$identity_document_type_id)
                            ->get()->transform(function($row) {
                                return [
                                    'id' => $row->id,
                                    'description' => $row->number.' - '.$row->name,
                                    'name' => $row->name,
                                    'number' => $row->number,
                                    'identity_document_type_id' => $row->identity_document_type_id,
                                    'identity_document_type_code' => $row->identity_document_type->code
                                ];
                            }); 

        return compact('customers');
    }

 
    public function create()
    {
        if(auth()->user()->type == 'integrator')
            return redirect('/documents');

        $is_contingency = 0;
        return view('tenant.documents.form', compact('is_contingency'));
    }
    

    public function tables()
    {
        $customers = $this->table('customers');
        $establishments = Establishment::where('id', auth()->user()->establishment_id)->get();// Establishment::all();
        $series = collect(Series::all())->transform(function($row) {
            return [
                'id' => $row->id,
                'contingency' => (bool) $row->contingency,
                'document_type_id' => $row->document_type_id,
                'establishment_id' => $row->establishment_id,
                'number' => $row->number
            ];
        });
        $document_types_invoice = DocumentType::whereIn('id', ['01', '03'])->get();
        $document_types_note = DocumentType::whereIn('id', ['07', '08'])->get();
        $note_credit_types = NoteCreditType::whereActive()->orderByDescription()->get();
        $note_debit_types = NoteDebitType::whereActive()->orderByDescription()->get();
        $currency_types = CurrencyType::whereActive()->get();
        $operation_types = OperationType::whereActive()->get();
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $company = Company::active();
        $document_type_03_filter = config('tenant.document_type_03_filter');
        $document_types_guide = DocumentType::whereIn('id', ['09', '31'])->get();
        $user = \auth()->user();

//        return compact('customers', 'establishments', 'series', 'document_types_invoice', 'document_types_note',
//                       'note_credit_types', 'note_debit_types', 'currency_types', 'operation_types',
//                       'discount_types', 'charge_types', 'company', 'document_type_03_filter',
//                       'document_types_guide');

        // return compact('customers', 'establishments', 'series', 'document_types_invoice', 'document_types_note',
        //                'note_credit_types', 'note_debit_types', 'currency_types', 'operation_types',
        //                'discount_types', 'charge_types', 'company', 'document_type_03_filter');

                       
        return compact( 'customers','establishments', 'series', 'document_types_invoice', 'document_types_note',
                        'note_credit_types', 'note_debit_types', 'currency_types', 'operation_types',
                        'discount_types', 'charge_types', 'company', 'document_type_03_filter',
                        'document_types_guide', 'user');

    }

    public function item_tables()
    {
        $items = $this->table('items');
        $categories = [];//Category::cascade();
        $affectation_igv_types = AffectationIgvType::whereActive()->get();
        $system_isc_types = SystemIscType::whereActive()->get();
        $price_types = PriceType::whereActive()->get();
        $operation_types = OperationType::whereActive()->get();
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $attribute_types = AttributeType::whereActive()->orderByDescription()->get();

        return compact('items', 'categories', 'affectation_igv_types', 'system_isc_types', 'price_types',
                       'operation_types', 'discount_types', 'charge_types', 'attribute_types');
    }

    public function table($table)
    {
        if ($table === 'customers') {
            $customers = Person::whereType('customers')->orderBy('name')->take(20)->get()->transform(function($row) {
                return [
                    'id' => $row->id,
                    'description' => $row->number.' - '.$row->name,
                    'name' => $row->name,
                    'number' => $row->number,
                    'identity_document_type_id' => $row->identity_document_type_id,
                    'identity_document_type_code' => $row->identity_document_type->code
                ];
            });
            return $customers;
        }
        if ($table === 'items') {
            $items = Item::whereWarehouse()->orderBy('description')->get();
            return collect($items)->transform(function($row) {
                $full_description = ($row->internal_id)?$row->internal_id.' - '.$row->description:$row->description;
                return [
                    'id' => $row->id,
                    'full_description' => $full_description,
                    'description' => $row->description,
                    'currency_type_id' => $row->currency_type_id,
                    'currency_type_symbol' => $row->currency_type->symbol,
                    'sale_unit_price' => $row->sale_unit_price,
                    'purchase_unit_price' => $row->purchase_unit_price,
                    'unit_type_id' => $row->unit_type_id,
                    'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                    'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id,
                    'calculate_quantity' => (bool) $row->calculate_quantity,
                    'has_igv' => (bool) $row->has_igv,
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
                    })
                    // 'warehouses' => collect($row->warehouses)->transform(function($row) {
                    //     return [
                    //         'warehouse_description' => $row->warehouse->description,
                    //         'stock' => $row->stock,
                    //     ];
                    // })
                ];
            });
//            return $items;
        }

        return [];
    }

    public function record($id)
    {
        $record = new DocumentResource(Document::findOrFail($id));

        return $record;
    }

    public function store(DocumentRequest $request)
    {
        $fact = DB::connection('tenant')->transaction(function () use ($request) {
            $facturalo = new Facturalo();
            $facturalo->save($request->all());
            $facturalo->createXmlUnsigned();
            $facturalo->signXmlUnsigned();
            $facturalo->updateHash();
            $facturalo->updateQr();
            $facturalo->createPdf();
            $facturalo->senderXmlSignedBill();

            return $facturalo;
        });

        $document = $fact->getDocument();
        $response = $fact->getResponse();

        return [
            'success' => true,
            'data' => [
                'id' => $document->id,
            ],
        ];
    }

    public function reStore($document_id)
    {
        $fact = DB::connection('tenant')->transaction(function () use ($document_id) {
            $document = Document::find($document_id);

            $type = 'invoice';
            if($document->document_type_id === '07') {
                $type = 'credit';
            }
            if($document->document_type_id === '08') {
                $type = 'debit';
            }

            $facturalo = new Facturalo();
            $facturalo->setDocument($document);
            $facturalo->setType($type);
            $facturalo->createXmlUnsigned();
            $facturalo->signXmlUnsigned();
            $facturalo->updateHash();
            $facturalo->updateQr();
            $facturalo->updateSoap('02', $type);
            $facturalo->updateState('01');
            $facturalo->createPdf($document, $type, 'ticket');
//            $facturalo->senderXmlSignedBill();
        });

//        $document = $fact->getDocument();
//        $response = $fact->getResponse();

        return [
            'success' => true,
            'message' => 'El documento se volvio a generar.',
        ];
    }

    public function email(DocumentEmailRequest $request)
    {
        $company = Company::active();
        $document = Document::find($request->input('id'));
        $customer_email = $request->input('customer_email');

        Mail::to($customer_email)->send(new DocumentEmail($company, $document));

        return [
            'success' => true
        ];
    }
    
    public function send($document_id) {
        $document = Document::find($document_id);
        
        $fact = DB::connection('tenant')->transaction(function () use ($document) {
            $facturalo = new Facturalo();
            $facturalo->setDocument($document);
            $facturalo->loadXmlSigned();
            $facturalo->onlySenderXmlSignedBill();
            return $facturalo;
        });
        
        $response = $fact->getResponse();
        
        return [
            'success' => true,
            'message' => $response['description'],
        ];
    }
    
    public function consultCdr($document_id)
    {
        $document = Document::find($document_id);

        $fact = DB::connection('tenant')->transaction(function () use ($document) {
            $facturalo = new Facturalo();
            $facturalo->setDocument($document);
            $facturalo->consultCdr();
        });

        $response = $fact->getResponse();

        return [
            'success' => true,
            'message' => $response['description'],
        ];
    }
    
    public function sendServer($document_id, $query = false) {
        $document = Document::find($document_id);
        $bearer = config('tenant.token_server');
        $api_url = config('tenant.url_server');
        $client = new Client(['base_uri' => $api_url]);
        
       // $zipFly = new ZipFly();
       
        $data_json = (array) $document->data_json;
        $data_json['external_id'] = $document->external_id;
        $data_json['hash'] = $document->hash;
        $data_json['qr'] = $document->qr;
        $data_json['query'] = $query;
        $data_json['file_xml_signed'] = base64_encode($this->getStorage($document->filename, 'signed'));
        $data_json['file_pdf'] = base64_encode($this->getStorage($document->filename, 'pdf'));
        
        $res = $client->post('/api/documents_server', [
            'http_errors' => false,
            'headers' => [
                'Authorization' => 'Bearer '.$bearer,
                'Accept' => 'application/json',
            ],
            'form_params' => $data_json
        ]);
        
        $response = json_decode($res->getBody()->getContents(), true);
        
        if ($response['success']) {
            $document->send_server = true;
            $document->save();
        }
        
        return $response;
    }
    
    public function checkServer($document_id) {
        $document = Document::find($document_id);
        $bearer = config('tenant.token_server');
        $api_url = config('tenant.url_server');
        
        $client = new Client(['base_uri' => $api_url]);
        
        $res = $client->get('/api/document_check_server/'.$document->external_id, [
            'headers' => [
                'Authorization' => 'Bearer '.$bearer,
                'Accept' => 'application/json',
            ],
        ]);
        
        $response = json_decode($res->getBody()->getContents(), true);
        
        if ($response['success']) {
            $state_type_id = $response['state_type_id'];
            $document->state_type_id = $state_type_id;
            $document->save();
            
            if ($state_type_id === '05') {
                $this->uploadStorage($document->filename, base64_decode($response['file_cdr']), 'cdr');
            }
        }
        
        return $response;
    }

    public function searchCustomerById($id)
    {        
   
        $customers = Person::whereType('customers')
                    ->where('id',$id) 
                    ->get()->transform(function($row) {
                        return [
                            'id' => $row->id,
                            'description' => $row->number.' - '.$row->name,
                            'name' => $row->name,
                            'number' => $row->number,
                            'identity_document_type_id' => $row->identity_document_type_id,
                            'identity_document_type_code' => $row->identity_document_type->code
                        ];
                    }); 

        return compact('customers');
    }

    public function getIdentityDocumentTypeId($document_type_id){

        if($document_type_id == '01'){
            $identity_document_type_id = [6];
        }else{
            if(config('tenant.document_type_03_filter')){
                $identity_document_type_id = [1];
            }else{
                $identity_document_type_id = [1,4,6,7,0];
            }
        } 

        return $identity_document_type_id;
    }

    public function changeToRegisteredStatus($document_id)
    {
        $document = Document::find($document_id);
        if($document->state_type_id === '01') {
            $document->state_type_id = '05';
            $document->save();

            return [
                'success' => true,
                'message' => 'El estado del documento fue actualizado.',
            ];
        }
    }
}
