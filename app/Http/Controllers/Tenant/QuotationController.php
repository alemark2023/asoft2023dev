<?php

namespace App\Http\Controllers\Tenant;

use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\CoreFacturalo\Requests\Inputs\Common\EstablishmentInput;
use App\CoreFacturalo\Requests\Inputs\Common\PersonInput;
use App\CoreFacturalo\Template;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SearchItemController;
use App\Http\Requests\Tenant\QuotationRequest;
use App\Http\Resources\Tenant\QuotationCollection;
use App\Http\Resources\Tenant\QuotationResource;
use App\Mail\Tenant\QuotationEmail;
use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\AttributeType;
use App\Models\Tenant\Catalogs\ChargeDiscountType;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Catalogs\OperationType;
use App\Models\Tenant\Catalogs\PriceType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\Company;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Item;
use App\Models\Tenant\PaymentMethodType;
use App\Models\Tenant\Person;
use App\Models\Tenant\Quotation;
use App\Models\Tenant\Series;
use App\Models\Tenant\StateType;
use App\Models\Tenant\User;
use App\Models\Tenant\Warehouse;
use App\Traits\OfflineTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\Finance\Traits\FinanceTrait;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Modules\Inventory\Models\Warehouse as ModuleWarehouse;


class QuotationController extends Controller
{

    use FinanceTrait;
    use OfflineTrait;
    use StorageDocument;

    protected $quotation;
    protected $company;

    public function index()
    {
        $company = Company::select('soap_type_id')->first();
        $soap_company = $company->soap_type_id;
        $generate_order_note_from_quotation = Configuration::getRecordIndividualColumn('generate_order_note_from_quotation');

        return view('tenant.quotations.index', compact('soap_company', 'generate_order_note_from_quotation'));
    }


    public function create($saleOpportunityId = null)
    {
        return view('tenant.quotations.form', compact('saleOpportunityId'));
    }

    public function edit($id)
    {
        $resourceId = $id;
        return view('tenant.quotations.form_edit', compact('resourceId'));
    }

    public function columns()
    {
        return [
            'customer' => 'Cliente',
            'date_of_issue' => 'Fecha de emisión',
            'delivery_date' => 'Fecha de entrega',
            'user_name' => 'Registrado por',
            'seller_name' => 'Vendedor',
            'referential_information' => 'Inf.Referencial',
            'number' => 'Número',
        ];
    }

    public function filter()
    {
        $state_types = StateType::whereIn('id', ['01', '05', '09'])->get();

        return compact('state_types');
    }

    public function records(Request $request)
    {
        // dd($request->all());
        $records = $this->getRecords($request);

        return new QuotationCollection($records->paginate(config('tenant.items_per_page')));
    }

    private function getRecords($request)
    {
        $column = $request->input('column');
        $value = $request->input('value');
        $query = Quotation::query();

        if ($column === 'user_name') {
            $query->whereHas('user', function ($q) use ($value) {
                $q->where('name', 'like', "%{$value}%");
            })
                ->whereTypeUser();
        } else if ($column === 'customer') {
            $query->whereHas('person', function ($q) use ($value) {
                $q->where('name', 'like', "%{$value}%")
                    ->orWhere('number', 'like', "%{$value}%");
            })
                ->whereTypeUser();

        } else if ($column === 'seller_name') {
            $query->whereHas('seller', function ($q) use ($value) {
                $q->where('name', 'like', "%{$value}%");
            });

        } else if ($column === 'number') {
            if (!is_null($value) && $value !== '') {
                $query->where('id', $value);
            }
        } else {
            $query->where($column, 'like', "%{$value}%")
                ->whereTypeUser();
        }

        $records = $query->latest();

        $form = json_decode($request->form);

        if ($form->date_start && $form->date_end) {
            $records = $records->whereBetween('date_of_issue', [$form->date_start, $form->date_end]);
        }

        $state_type_id = $form->state_type_id ?? null;
        if($state_type_id) $records->where('state_type_id', $state_type_id);

        return $records;
    }

    public function searchCustomers(Request $request)
    {

        $customers = Person::whereType('customers')
            ->orderBy('name')
            ->whereIsEnabled();
        if ($request->has('customer_id')) {
            $customers->where('id', $request->customer_id);
        } else {
            $customers->where('number', 'like', "%{$request->input}%")
                ->orWhere('name', 'like', "%{$request->input}%");
        }
        $customers = $customers->get()->transform(function ($row) {
            /** @var Person $row */
            return $row->getCollectionData();
            /* Se ha movido al modelo */
            return [
                'id' => $row->id,
                'description' => $row->number . ' - ' . $row->name,
                'name' => $row->name,
                'number' => $row->number,
                'identity_document_type_id' => $row->identity_document_type_id,
                'identity_document_type_code' => $row->identity_document_type->code,
                'addresses' => $row->addresses,
                'address' => $row->address,
            ];
        });
        return compact('customers');
    }


    public function tables()
    {

        $customers = $this->table('customers');
        $establishments = Establishment::where('id', auth()->user()->establishment_id)->get();
        $currency_types = CurrencyType::whereActive()->get();
        // $document_types_invoice = DocumentType::whereIn('id', ['01', '03'])->get();
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $company = Company::active();
        $document_type_03_filter = config('tenant.document_type_03_filter');
        $payment_method_types = PaymentMethodType::orderBy('id', 'desc')->get();
        $payment_destinations = $this->getPaymentDestinations();
        $configuration = Configuration::select('destination_sale')->first();
        /*
        carlomagno83/facturadorpro4#233

        $sellers = User::without(['establishment'])
            ->whereIn('type', ['seller'])
            ->orWhere('id', auth()->user()->id)
            ->get();
        */
        $sellers = User::GetSellers(false)->get();

        return compact('customers', 'establishments', 'currency_types', 'discount_types', 'charge_types', 'configuration',
            'company', 'document_type_03_filter', 'payment_method_types', 'payment_destinations', 'sellers');

    }


    public function option_tables()
    {
        $establishment = Establishment::where('id', auth()->user()->establishment_id)->first();
        $series = Series::where('establishment_id', $establishment->id)->get();
        $document_types_invoice = DocumentType::whereIn('id', ['01', '03'])->get();
        // $payment_method_types = PaymentMethodType::all();
        $payment_method_types = PaymentMethodType::getPaymentMethodTypes();
        $payment_destinations = $this->getPaymentDestinations();
        // $sellers = User::GetSellers(true)->get();
        $sellers = User::where('establishment_id', auth()->user()->establishment_id)->whereIn('type', ['seller', 'admin'])->orWhere('id', auth()->user()->id)->get();

        return compact('series', 'document_types_invoice', 'payment_method_types', 'payment_destinations', 'sellers');
    }

    public function item_tables()
    {
        // $items = $this->table('items');
        $items = SearchItemController::getItemsToQuotation();
        $categories = [];
        $affectation_igv_types = AffectationIgvType::whereActive()->get();
        $system_isc_types = SystemIscType::whereActive()->get();
        $price_types = PriceType::whereActive()->get();
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $attribute_types = AttributeType::whereActive()->orderByDescription()->get();
        $is_client = $this->getIsClient();
        $operation_types = OperationType::whereActive()->get();

        return compact(
            'items',
            'categories',
            'operation_types',
            'affectation_igv_types',
            'system_isc_types',
            'price_types',
            'discount_types',
            'charge_types',
            'attribute_types',
            'is_client'
        );
    }

    public function record($id)
    {
        $record = new QuotationResource(Quotation::findOrFail($id));

        return $record;
    }

    public function record2($id)
    {
        $record = new QuotationResource(Quotation::findOrFail($id));

        return $record;
    }


    public function getFullDescription($row)
    {

        $desc = ($row->internal_id) ? $row->internal_id . ' - ' . $row->description : $row->description;
        $category = ($row->category) ? " - {$row->category->name}" : "";
        $brand = ($row->brand) ? " - {$row->brand->name}" : "";

        $desc = "{$desc} {$category} {$brand}";

        return $desc;
    }

    public function store(QuotationRequest $request)
    {
        DB::connection('tenant')->transaction(function () use ($request) {

            $data = $this->mergeData($request);
            $data['terms_condition'] = $this->getTermsCondition();

            $this->quotation = Quotation::create($data);

            foreach ($data['items'] as $row) {
                $this->quotation->items()->create($row);
            }

            $this->savePayments($this->quotation, $data['payments']);

            $this->setFilename();
            $this->createPdf($this->quotation, "a4", $this->quotation->filename);

        });

        return [
            'success' => true,
            'data' => [
                'id' => $this->quotation->id,
                'number_full' => $this->quotation->number_full,
            ],
        ];
    }

    public function update(QuotationRequest $request)
    {

        DB::connection('tenant')->transaction(function () use ($request) {
            // $data = $this->mergeData($request);
            // return $request['id'];
            $configuration = Configuration::select('terms_condition')->first();
            $request['terms_condition'] = $this->getTermsCondition();

            $this->quotation = Quotation::firstOrNew(['id' => $request['id']]);
            $this->quotation->fill($request->all());
            $this->quotation->customer = PersonInput::set($request['customer_id'], isset($request['customer_address_id']) ? $request['customer_address_id'] : null);
            $this->quotation->items()->delete();

            $this->deleteAllPayments($this->quotation->payments);

            foreach ($request['items'] as $row) {

                $this->quotation->items()->create($row);
            }

            $this->savePayments($this->quotation, $request['payments']);

            $this->setFilename();
        });

        return [
            'success' => true,
            'data' => [
                'id' => $this->quotation->id,
            ],
        ];

    }

    public function getTermsCondition()
    {

        $configuration = Configuration::select('terms_condition')->first();

        if ($configuration) {
            return $configuration->terms_condition;
        }

        return null;

    }


    public function duplicate(Request $request)
    {
        // return $request->id;
        $obj = Quotation::find($request->id);
        $this->quotation = $obj->replicate();
        $this->quotation->external_id = Str::uuid()->toString();
        $this->quotation->state_type_id = '01';
        $this->quotation->save();

        foreach ($obj->items as $row) {
            $new = $row->replicate();
            $new->quotation_id = $this->quotation->id;
            $new->save();
        }

        $this->setFilename();

        return [
            'success' => true,
            'data' => [
                'id' => $this->quotation->id,
            ],
        ];

    }

    public function anular($id)
    {
        $obj = Quotation::find($id);
        $obj->state_type_id = 11;
        $obj->save();
        return [
            'success' => true,
            'message' => 'Producto anulado con éxito'
        ];
    }

    public function mergeData($inputs)
    {

        $this->company = Company::active();

        $values = [
            'user_id' => auth()->id(),
            'external_id' => Str::uuid()->toString(),
            'customer' => PersonInput::set($inputs['customer_id'], isset($inputs['customer_address_id']) ? $inputs['customer_address_id'] : null),
            'establishment' => EstablishmentInput::set($inputs['establishment_id']),
            'soap_type_id' => $this->company->soap_type_id,
            'state_type_id' => '01'
        ];

        $inputs->merge($values);

        return $inputs->all();
    }


    private function setFilename()
    {

        $name = [$this->quotation->prefix, $this->quotation->id, date('Ymd')];
        $this->quotation->filename = join('-', $name);
        $this->quotation->save();

    }


    public function table($table)
    {
        switch ($table) {
            case 'customers':

                $customers = Person::whereType('customers')->whereIsEnabled()->orderBy('name')->take(20)->get()->transform(function ($row) {
                    /** @var Person $row */
                    return $row->getCollectionData();
                    /** Se ha movido al modelo */
                    return [
                        'id' => $row->id,
                        'description' => $row->number . ' - ' . $row->name,
                        'name' => $row->name,
                        'number' => $row->number,
                        'identity_document_type_id' => $row->identity_document_type_id,
                        'identity_document_type_code' => $row->identity_document_type->code,
                        'addresses' => $row->addresses,
                        'address' => $row->address
                    ];
                });
                return $customers;

                break;

            case 'items':

                $warehouse = Warehouse::where('establishment_id', auth()->user()->establishment_id)->first();

                $items = Item::orderBy('description')->whereIsActive()
                    // ->with(['warehouses' => function($query) use($warehouse){
                    //     return $query->where('warehouse_id', $warehouse->id);
                    // }])
                    ->take(20)->get();

                $this->ReturnItem($items);

                return $items;

                break;
            default:
                return [];

                break;
        }
    }


    /**
     * Realiza la busqueda de producto en cotizacion.
     * @param Request $request
     * @return array
     */
    public function searchItems(Request $request)
    {
        $items = SearchItemController::getItemsToQuotation($request);
        return compact('items');

    }

    /**
     * Normaliza la salida de la colección de items para su consumo en las funciones.
     *
     */
    public function ReturnItem(&$item)
    {
        $configuration = Configuration::first();
        $establishment_id = auth()->user()->establishment_id;
        $warehouse = \Modules\Inventory\Models\Warehouse::where('establishment_id', $establishment_id)->first();

        $item->transform(function ($row) use ($configuration, $warehouse) {
            /** @var \App\Models\Tenant\Item $row */
            return $row->getDataToItemModal($warehouse, false, true);
            /** Se ha movido al modelo*/
            $full_description = $this->getFullDescription($row);
            return [
                'id' => $row->id,
                'full_description' => $full_description,
                'description' => $row->description,
                'currency_type_id' => $row->currency_type_id,
                'model' => $row->model,
                'brand' => $row->brand,
                'currency_type_symbol' => $row->currency_type->symbol,
                'sale_unit_price' => $row->sale_unit_price,
                'purchase_unit_price' => $row->purchase_unit_price,
                'unit_type_id' => $row->unit_type_id,
                'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id,
                'is_set' => (bool)$row->is_set,
                'has_igv' => (bool)$row->has_igv,
                'calculate_quantity' => (bool)$row->calculate_quantity,
                'item_unit_types' => collect($row->item_unit_types)->transform(function ($row) {
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
                'warehouses' => collect($row->warehouses)->transform(function ($row) {
                    return [
                        'warehouse_id' => $row->warehouse->id,
                        'warehouse_description' => $row->warehouse->description,
                        'stock' => $row->stock,

                    ];
                }),

            ];
        });
    }

    public function searchItemById($id)
    {

        $items = SearchItemController::getItemsToQuotation(null, $id);
        return compact('items');

    }


    public function searchCustomerById($id)
    {
        return $this->searchClientById($id);

    }

    public function download($external_id, $format)
    {
        $quotation = Quotation::where('external_id', $external_id)->first();

        if (!$quotation) throw new Exception("El código {$external_id} es inválido, no se encontro la cotización relacionada");

        $this->reloadPDF($quotation, $format, $quotation->filename);

        return $this->downloadStorage($quotation->filename, 'quotation');
    }

    public function toPrint($external_id, $format)
    {
        $quotation = Quotation::where('external_id', $external_id)->first();

        if (!$quotation) throw new Exception("El código {$external_id} es inválido, no se encontro la cotización relacionada");

        $this->reloadPDF($quotation, $format, $quotation->filename);
        $temp = tempnam(sys_get_temp_dir(), 'quotation');

        file_put_contents($temp, $this->getStorage($quotation->filename, 'quotation'));

        /*
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$quotation->filename.'"'
        ];
        */

        return response()->file($temp, $this->generalPdfResponseFileHeaders($quotation->filename));
    }

    private function reloadPDF($quotation, $format, $filename)
    {
        $this->createPdf($quotation, $format, $filename);
    }

    public function createPdf($quotation = null, $format_pdf = null, $filename = null)
    {
        ini_set("pcre.backtrack_limit", "5000000");
        $template = new Template();
        $pdf = new Mpdf();

        $document = ($quotation != null) ? $quotation : $this->quotation;
        $company = ($this->company != null) ? $this->company : Company::active();
        $filename = ($filename != null) ? $filename : $this->quotation->filename;

        $configuration = Configuration::first();

        $base_template = Establishment::find($document->establishment_id)->template_pdf;

        $html = $template->pdf($base_template, "quotation", $company, $document, $format_pdf);

        if ($format_pdf === 'ticket' or $format_pdf === 'ticket_80') {

            $width = 78;
            if (config('tenant.enabled_template_ticket_80')) $width = 76;

            $company_name = (strlen($company->name) / 20) * 10;
            $company_address = (strlen($document->establishment->address) / 30) * 10;
            $company_number = $document->establishment->telephone != '' ? '10' : '0';
            $customer_name = strlen($document->customer->name) > '25' ? '10' : '0';
            $customer_address = (strlen($document->customer->address) / 200) * 10;
            $p_order = $document->purchase_order != '' ? '10' : '0';

            $total_exportation = $document->total_exportation != '' ? '10' : '0';
            $total_free = $document->total_free != '' ? '10' : '0';
            $total_unaffected = $document->total_unaffected != '' ? '10' : '0';
            $total_exonerated = $document->total_exonerated != '' ? '10' : '0';
            $total_taxed = $document->total_taxed != '' ? '10' : '0';
            $quantity_rows = count($document->items);
            $payments = $document->payments()->count() * 5;
            $discount_global = 0;
            $terms_condition = $document->terms_condition ? 15 : 0;
            $contact = $document->contact ? 15 : 0;

            $document_description = ($document->description) ? count(explode("\n", $document->description)) * 3 : 0;


            foreach ($document->items as $it) {
                if ($it->discounts) {
                    $discount_global = $discount_global + 1;
                }
            }
            $legends = $document->legends != '' ? '10' : '0';

            $pdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => [
                    $width,
                    120 +
                    ($quantity_rows * 8) +
                    ($discount_global * 3) +
                    $company_name +
                    $company_address +
                    $company_number +
                    $customer_name +
                    $customer_address +
                    $p_order +
                    $legends +
                    $total_exportation +
                    $total_free +
                    $total_unaffected +
                    $payments +
                    $total_exonerated +
                    $terms_condition +
                    $contact +
                    $document_description +
                    $total_taxed],
                'margin_top' => 2,
                'margin_right' => 5,
                'margin_bottom' => 0,
                'margin_left' => 5
            ]);
        } else if ($format_pdf === 'a5') {

            $company_name = (strlen($company->name) / 20) * 10;
            $company_address = (strlen($document->establishment->address) / 30) * 10;
            $company_number = $document->establishment->telephone != '' ? '10' : '0';
            $customer_name = strlen($document->customer->name) > '25' ? '10' : '0';
            $customer_address = (strlen($document->customer->address) / 200) * 10;
            $p_order = $document->purchase_order != '' ? '10' : '0';

            $total_exportation = $document->total_exportation != '' ? '10' : '0';
            $total_free = $document->total_free != '' ? '10' : '0';
            $total_unaffected = $document->total_unaffected != '' ? '10' : '0';
            $total_exonerated = $document->total_exonerated != '' ? '10' : '0';
            $total_taxed = $document->total_taxed != '' ? '10' : '0';
            $quantity_rows = count($document->items);
            $discount_global = 0;
            foreach ($document->items as $it) {
                if ($it->discounts) {
                    $discount_global = $discount_global + 1;
                }
            }
            $legends = $document->legends != '' ? '10' : '0';


            $alto = ($quantity_rows * 8) +
                ($discount_global * 3) +
                $company_name +
                $company_address +
                $company_number +
                $customer_name +
                $customer_address +
                $p_order +
                $legends +
                $total_exportation +
                $total_free +
                $total_unaffected +
                $total_exonerated +
                $total_taxed;
            $diferencia = 148 - (float)$alto;

            $pdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => [
                    210,
                    $diferencia + $alto
                ],
                'margin_top' => 2,
                'margin_right' => 5,
                'margin_bottom' => 0,
                'margin_left' => 5
            ]);


        } else {


            $pdf_font_regular = config('tenant.pdf_name_regular');
            $pdf_font_bold = config('tenant.pdf_name_bold');

            if ($pdf_font_regular != false) {
                $defaultConfig = (new ConfigVariables())->getDefaults();
                $fontDirs = $defaultConfig['fontDir'];

                $defaultFontConfig = (new FontVariables())->getDefaults();
                $fontData = $defaultFontConfig['fontdata'];

                $default = [
                    'fontDir' => array_merge($fontDirs, [
                        app_path('CoreFacturalo' . DIRECTORY_SEPARATOR . 'Templates' .
                            DIRECTORY_SEPARATOR . 'pdf' .
                            DIRECTORY_SEPARATOR . $base_template .
                            DIRECTORY_SEPARATOR . 'font')
                    ]),
                    'fontdata' => $fontData + [
                            'custom_bold' => [
                                'R' => $pdf_font_bold . '.ttf',
                            ],
                            'custom_regular' => [
                                'R' => $pdf_font_regular . '.ttf',
                            ],
                        ]
                ];

                if ($base_template == 'citec') {
                    $default = [
                        'mode' => 'utf-8',
                        'margin_top' => 2,
                        'margin_right' => 0,
                        'margin_bottom' => 0,
                        'margin_left' => 0,
                        'fontDir' => array_merge($fontDirs, [
                            app_path('CoreFacturalo' . DIRECTORY_SEPARATOR . 'Templates' .
                                DIRECTORY_SEPARATOR . 'pdf' .
                                DIRECTORY_SEPARATOR . $base_template .
                                DIRECTORY_SEPARATOR . 'font')
                        ]),
                        'fontdata' => $fontData + [
                                'custom_bold' => [
                                    'R' => $pdf_font_bold . '.ttf',
                                ],
                                'custom_regular' => [
                                    'R' => $pdf_font_regular . '.ttf',
                                ],
                            ]
                    ];

                }

                $pdf = new Mpdf($default);
            }
        }

        $path_css = app_path('CoreFacturalo' . DIRECTORY_SEPARATOR . 'Templates' .
            DIRECTORY_SEPARATOR . 'pdf' .
            DIRECTORY_SEPARATOR . $base_template .
            DIRECTORY_SEPARATOR . 'style.css');

        $stylesheet = file_get_contents($path_css);

        $pdf->WriteHTML($stylesheet, HTMLParserMode::HEADER_CSS);
        // $pdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

        if ($format_pdf != 'ticket') {
            if (config('tenant.pdf_template_footer')) {

                $html_footer = $template->pdfFooter($base_template, $this->quotation);
                $html_footer_term_condition = ($document->terms_condition) ? $template->pdfFooterTermCondition($base_template, $document) : "";

                $html_footer_legend = "";
                if ($configuration->legend_footer) {
                    $html_footer_legend = $template->pdfFooterLegend($base_template, $this->quotation);
                }

                $html_footer_images = "";
                $this->setPdfFooterImages($html_footer_images, $configuration, $format_pdf, $template, $base_template);

                $pdf->setAutoBottomMargin = 'stretch';

                $pdf->SetHTMLFooter($html_footer_term_condition . $html_footer_images . $html_footer . $html_footer_legend);
                // $pdf->SetHTMLFooter($html_footer_term_condition . $html_footer . $html_footer_legend);
                
            }
            //$html_footer = $template->pdfFooter();
            //$pdf->SetHTMLFooter($html_footer);
        }

        $pdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

        $this->uploadFile($filename, $pdf->output('', 'S'), 'quotation');
    }

    
    /**
     * Asignar imagenes en footer
     *
     * @param  string $html_footer_images
     * @param  Configuration $configuration
     * @param  string $format_pdf
     * @param  Template $template
     * @param  string $base_template
     * @return void
     */
    public function setPdfFooterImages(&$html_footer_images, $configuration, $format_pdf, $template, $base_template)
    {
        if($format_pdf === 'a4' && $configuration->applyImagesInPdfFooter() && in_array($base_template, ['default', 'default3']))
        {
            $html_footer_images = $template->pdfFooterImages($base_template, $configuration->getBase64PdfFooterImages());
        }
    }


    public function uploadFile($filename, $file_content, $file_type)
    {
        $this->uploadStorage($filename, $file_content, $file_type);
    }

    public function email(Request $request)
    {
        $request->validate([
            'customer_email' => 'required|email'
        ]);

        $client = Person::find($request->customer_id);
        $quotation = Quotation::find($request->id);
        $customer_email = $request->input('customer_email');

        // $this->reloadPDF($quotation, "a4", $quotation->filename);

        $email = $customer_email;
        $mailable = new QuotationEmail($client, $quotation);
        $id = (int)$request->id;
        $sendIt = EmailController::SendMail($email, $mailable, $id, 3);
        /*
        Configuration::setConfigSmtpMail();
        $array_email = explode(',', $customer_email);
        if (count($array_email) > 1) {
            foreach ($array_email as $email_to) {
                $email_to = trim($email_to);
                if(!empty($email_to)) {
                    Mail::to($email_to)->send(new QuotationEmail($client, $quotation));
                }
            }
        } else {
            Mail::to($customer_email)->send(new QuotationEmail($client, $quotation));
        }
        */
        return [
            'success' => true
        ];
    }


    public function savePayments($quotation, $payments)
    {

        foreach ($payments as $payment) {

            $record_payment = $quotation->payments()->create($payment);

            if (isset($payment['payment_destination_id'])) {
                $this->createGlobalPayment($record_payment, $payment);
            }
        }
    }

    public function changed($id)
    {
        $record = Quotation::find($id);
        $record->changed = true;
        $record->save();

        return [
            'success' => true
        ];
    }

    public function updateStateType($state_type_id, $id)
    {
        $record = Quotation::find($id);
        $record->state_type_id = $state_type_id;
        $record->save();

        return [
            'success' => true,
            'message' => 'Estado actualizado correctamente'
        ];
    }


    public function itemWarehouses($item_id)
    {

        $record = Item::find($item_id);
        // dd($record->warehouses);

        $establishment_id = auth()->user()->establishment_id;
        $warehouse = ModuleWarehouse::where('establishment_id', $establishment_id)->first();

        return collect($record->warehouses)->transform(function ($row) use ($warehouse) {
            return [
                'warehouse_description' => $row->warehouse->description,
                'stock' => $row->stock,
                'warehouse_id' => $row->warehouse_id,
                'checked' => ($row->warehouse_id == $warehouse->id) ? true : false,
            ];
        });

    }
}
