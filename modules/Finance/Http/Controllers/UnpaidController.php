<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\GlobalPayment;
use App\Models\Tenant\Cash;
use App\Models\Tenant\BankAccount;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Tenant\Company;
use Modules\Finance\Traits\FinanceTrait;
use Modules\Finance\Http\Resources\GlobalPaymentCollection;
use Modules\Finance\Exports\BalanceExport;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Tenant\Establishment;
use Carbon\Carbon;
use App\Models\Tenant\Person;
use Modules\Dashboard\Helpers\DashboardView;
use App\Exports\AccountsReceivable;
use App\Models\Tenant\Configuration;
use Modules\Finance\Exports\UnpaidPaymentMethodDayExport;
use App\Models\Tenant\User;
use App\Models\Tenant\PaymentMethodType;
use Modules\Finance\Http\Resources\UnpaidCollection;
use Modules\Finance\Traits\UnpaidTrait;
use Modules\Item\Models\WebPlatform;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\Document;
use ErrorException;
use Exception;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use App\CoreFacturalo\Template;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;

class UnpaidController extends Controller
{

    use FinanceTrait, UnpaidTrait;
    use StorageDocument;

    protected $sale_note;
    protected $company;

    public function index()
    {
        return view('finance::unpaid.index');
    }

    public function filter()
    {
        $customer_temp = Person::whereType('customers')->orderBy('name')->get()->transform(function($row) {
            return [
                'id' => $row->id,
                'description' => $row->number.' - '.$row->name,
                'name' => $row->name,
                'number' => $row->number,
                'identity_document_type_id' => $row->identity_document_type_id,
            ];
        });
        $customer= [];
        $customer[]=[
            'id' => null,
            'description' => 'Todos',
            'name' => 'Todos',
            'number' => '',
            'identity_document_type_id' => '',
        ];
        $customers = array_merge($customer,$customer_temp->toArray());

        $establishments = DashboardView::getEstablishments();

        $users = [];

        if(auth()->user()->type == 'admin')
        {
            $users = User::where('id', '!=', auth()->user()->id)->whereIn('type', ['admin', 'seller'])->get();
        }

        $payment_method_types = PaymentMethodType::whereIn('id', ['05', '08', '09'])->get();
        $web_platforms = WebPlatform::all();

        return compact('customers', 'establishments', 'users', 'payment_method_types','web_platforms');
    }

    public function records(Request $request)
    {

        $records = (new DashboardView())->getUnpaidFilterUser($request->all());
        $config = Configuration::first();
        return (new UnpaidCollection($records->paginate(config('tenant.items_per_page'))))->additional([
            'configuration' => $config->finances
        ]);

    }

    public function unpaidall()
    {
        return Excel::download(new AccountsReceivable, 'Allclients.xlsx');
    }


    public function reportPaymentMethodDays(Request $request)
    {

        $all_records = $this->transformRecords((new DashboardView())->getUnpaidFilterUser($request->all())->get());

        $records = collect($all_records)->where('total_to_pay', '>', 0)->where('type', 'document')->map(function($row){
            $row['difference_days'] = Carbon::parse($row['date_of_issue'])->diffInDays($row['date_of_due']);
            return $row;
        });

        $company = Company::first();

        $unpaidPaymentMethodDayExport = new UnpaidPaymentMethodDayExport();
        $unpaidPaymentMethodDayExport
            ->company($company)
            ->records($records);
        return $unpaidPaymentMethodDayExport->download('Reporte_C_Cobrar_F_Pago'.Carbon::now().'.xlsx');

    }


    public function pdf(Request $request) {

        $records = $this->transformRecords((new DashboardView())->getUnpaidFilterUser($request->all())->get());

        $company = Company::first();

        $pdf = PDF::loadView('finance::unpaid.reports.report_pdf', compact("records", "company"));

        $filename = 'Reporte_Cuentas_Por_Cobrar_'.date('YmdHis');

        return $pdf->download($filename.'.pdf');

    }

    public function toPrint($external_id,$type) {
        if ($type=='sale') {
            $sale_note = SaleNote::where('external_id', $external_id)->first();
        } else {
            $sale_note = Document::where('external_id', $external_id)->first();
        }
        

        if (!$sale_note) throw new Exception("El código {$external_id} es inválido, no se encontro la nota de venta relacionada");
        $format='a4';
        $this->reloadPDF($sale_note, $format, $sale_note->filename);
        $temp = tempnam(sys_get_temp_dir(), 'unpaid');

        file_put_contents($temp, $this->getStorage($sale_note->filename, 'unpaid'));

        return response()->file($temp);
    }

    private function reloadPDF($sale_note, $format, $filename) {
        $this->createPdf($sale_note, $format, $filename);
    }

    public function createPdf($sale_note = null, $format_pdf = null, $filename = null) {

        ini_set("pcre.backtrack_limit", "5000000");
        $template = new Template();
        $pdf = new Mpdf();

        $this->company = ($this->company != null) ? $this->company : Company::active();
        $this->document = ($sale_note != null) ? $sale_note : $this->sale_note;

        $this->configuration = Configuration::first();
        // $configuration = $this->configuration->formats;
        $base_template = Establishment::find($this->document->establishment_id)->template_pdf;

        $html = $template->pdf($base_template, "unpaid", $this->company, $this->document, $format_pdf);

        /* cuentas por cobrar formato a4 */

            $pdf_font_regular = config('tenant.pdf_name_regular');
            $pdf_font_bold = config('tenant.pdf_name_bold');

            if ($pdf_font_regular != false) {
                $defaultConfig = (new ConfigVariables())->getDefaults();
                $fontDirs = $defaultConfig['fontDir'];

                $defaultFontConfig = (new FontVariables())->getDefaults();
                $fontData = $defaultFontConfig['fontdata'];

                $pdf = new Mpdf([
                    'fontDir' => array_merge($fontDirs, [
                        app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.
                                                DIRECTORY_SEPARATOR.'pdf'.
                                                DIRECTORY_SEPARATOR.$base_template.
                                                DIRECTORY_SEPARATOR.'font')
                    ]),
                    'fontdata' => $fontData + [
                        'custom_bold' => [
                            'R' => $pdf_font_bold.'.ttf',
                        ],
                        'custom_regular' => [
                            'R' => $pdf_font_regular.'.ttf',
                        ],
                    ]
                ]);
            }


        $path_css = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.
                                             DIRECTORY_SEPARATOR.'pdf'.
                                             DIRECTORY_SEPARATOR.$base_template.
                                             DIRECTORY_SEPARATOR.'style.css');

        $stylesheet = file_get_contents($path_css);

        $pdf->WriteHTML($stylesheet, HTMLParserMode::HEADER_CSS);
        $pdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

        
        $this->uploadFile($this->document->filename, $pdf->output('', 'S'), 'unpaid');
    }

    public function uploadFile($filename, $file_content, $file_type)
    {
        $this->uploadStorage($filename, $file_content, $file_type);
    }

}
