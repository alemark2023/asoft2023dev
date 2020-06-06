<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
//use Illuminate\Support\Str;
//use App\Http\Requests\Tenant\OrderRequest;
use App\Http\Resources\Tenant\OrderCollection;
use App\Http\Resources\Tenant\OrderResource;
use Exception;
use Illuminate\Http\Request;
use App\Models\Tenant\Order;
use App\Models\Tenant\ItemWarehouse;
use App\Http\Resources\Tenant\ItemWarehouseCollection;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Company;
use App\CoreFacturalo\Facturalo;
use App\Models\Tenant\Configuration;
use App\CoreFacturalo\Template;
use Mpdf\Mpdf;
use Mpdf\HTMLParserMode;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;

class OrderController extends Controller
{

  use StorageDocument;

  protected $company;

    public function index()
    {
        return view('tenant.orders.index');
    }

    public function columns()
    {
        return [
            'id' => 'Codigo de Pedido',
            'number_document' => 'Comprobante Electronico',
        ];
    }

    public function records(Request $request)
    {
        $records = Order::where($request->column, 'like', "%{$request->value}%")->latest();

        return new OrderCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function updateStatusOrders(Request $request)
    {
      
      if ($request->record['status_order_id'] == 3) {
        for ($i=0; $i <= count($request->discount)-1; $i++) {
          if (isset($request->discount[$i]['id'])) {
            $itemWarehouse = ItemWarehouse::where('id', $request->discount[$i]['id'])->first();

            //if ($itemWarehouse->stock >= $request->discount[$i]['cantidad']) {
              ItemWarehouse::where('id', $itemWarehouse->id)->update(['stock' => ($itemWarehouse->stock - $request->discount[$i]['cantidad'])]);

            //}
          }
        }
        Order::where('id', $request->record['id'])->update(['status_order_id' => $request->record['status_order_id']]);

        return [
          'message' => 'Estatus y Stock actualizado'
        ];
      }

      Order::where('id', $request->record['id'])->update(['status_order_id' => $request->record['status_order_id']]);
      return [
        'message' => 'Estatus actualizado'
      ];

    }

    public function searchWarehouse(Request $request)
    {
      $product = ItemWarehouse::whereIn('item_id', $request->item_id)->orderBy('item_id')->get();
      return new ItemWarehouseCollection($product);
    }

    public function pdf(Request $request) {

      $company = Company::first();
      $establishment = Establishment::first();
      $records = Order::find($request->id);
      $customer = $records->customer;

      $pdf = PDF::loadView('tenant.reports.orders.report_pdf', compact("records", "company", "establishment", "customer"));
      $filename = 'Factura_Pedidos'.date('YmdHis');
      return $pdf->download($filename.'.pdf');
    }

    public function record($id)
    {
      $record = new OrderResource(Order::findOrFail($id));
      return $record;
    }

    public function toPrint($external_id, $format) {

      $order = Order::where('external_id', $external_id)->first();

      if (!$order) throw new Exception("El código {$external_id} es inválido, no se encontro el pedido relacionado");

      $this->reloadPDF($order, $format, 'pedido');
      $temp = tempnam(sys_get_temp_dir(), 'pedido');

      file_put_contents($temp, $this->getStorage('pedido', 'pedido'));

      return response()->file($temp);
    }

    private function reloadPDF($order, $format_pdf, $type) {
      ini_set("pcre.backtrack_limit", "5000000");
      $template = new Template();
      $pdf = new Mpdf();
      
      $this->company = ($this->company != null) ? $this->company : Company::active();
      $this->establishment = Establishment::first();
      $this->document = ($order != null) ? $order : $this->order;
          
      $this->configuration = Configuration::first();
      $configuration = $this->configuration->formats;
      $base_template = $configuration;

      // $format_pdf = ticket
      //dd($this->establishment);
      //dd(count($this->document->items));
      
      $html = $template->pdf($base_template, "pedido", $this->company, $this->document, $format_pdf);
      
      if ($format_pdf === 'ticket') {

        $width = ($format_pdf === 'ticket_58') ? 56 : 78 ;
        if(config('tenant.enabled_template_ticket_80')) $width = 76;

        $company_logo      = ($this->company->logo) ? 40 : 0;
        $company_name      = (strlen($this->company->name) / 20) * 10;
        $company_address   = (strlen($this->establishment->address) / 30) * 10;
        $company_number    = $this->establishment->telephone != '' ? '10' : '0';
        $customer_name     = strlen($this->document->customer->apellidos_y_nombres_o_razon_social) > '25' ? '10' : '0';
        $customer_address  = (strlen($this->document->customer->direccion) / 200) * 10;
        //$p_order           = $this->document->purchase_order != '' ? '10' : '0';

        /*$total_exportation = $this->document->total_exportation != '' ? '10' : '0';
        $total_free        = $this->document->total_free != '' ? '10' : '0';
        $total_unaffected  = $this->document->total_unaffected != '' ? '10' : '0';
        $total_exonerated  = $this->document->total_exonerated != '' ? '10' : '0';
        $total_taxed       = $this->document->total_taxed != '' ? '10' : '0';*/
        $quantity_rows     = count($this->document->items);
        //$payments     = $this->document->payments()->count() * 2;

        $extra_by_item_description = 0;
        $discount_global = 0;
        foreach ($this->document->items as $it) {
            if(strlen($it->description)>100){
                $extra_by_item_description +=24;
            }
            /*if ($it->discounts) {
                $discount_global = $discount_global + 1;
            }*/
        }
        //$legends = $this->document->legends != '' ? '10' : '0';


              $pdf = new Mpdf([
                  'mode' => 'utf-8',
                  'format' => [
                      $width,
                      200 +
                      (($quantity_rows * 8) + $extra_by_item_description) +
                      ($discount_global * 3) +
                      $company_logo +
                      //$payments +
                      $company_name +
                      $company_address +
                      $company_number +
                      $customer_name +
                      $customer_address /* +
                      $p_order +
                      $legends +
                      $total_exportation +
                      $total_free +
                      $total_unaffected +
                      $total_exonerated +
                  $total_taxed*/],
                  'margin_top' => 0,
                  'margin_right' => 2,
                  'margin_bottom' => 0,
                  'margin_left' => 2
              ]);
          } /*else if($format_pdf === 'a5'){

              $company_name      = (strlen($this->company->name) / 20) * 10;
              $company_address   = (strlen($this->document->establishment->address) / 30) * 10;
              $company_number    = $this->document->establishment->telephone != '' ? '10' : '0';
              $customer_name     = strlen($this->document->customer->name) > '25' ? '10' : '0';
              $customer_address  = (strlen($this->document->customer->address) / 200) * 10;
              $p_order           = $this->document->purchase_order != '' ? '10' : '0';

              $total_exportation = $this->document->total_exportation != '' ? '10' : '0';
              $total_free        = $this->document->total_free != '' ? '10' : '0';
              $total_unaffected  = $this->document->total_unaffected != '' ? '10' : '0';
              $total_exonerated  = $this->document->total_exonerated != '' ? '10' : '0';
              $total_taxed       = $this->document->total_taxed != '' ? '10' : '0';
              $quantity_rows     = count($this->document->items);
              $discount_global = 0;
              foreach ($this->document->items as $it) {
                  if ($it->discounts) {
                      $discount_global = $discount_global + 1;
                  }
              }
              $legends           = $this->document->legends != '' ? '10' : '0';


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

          }
          */

          $path_css = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.
                                              DIRECTORY_SEPARATOR.'pdf'.
                                              DIRECTORY_SEPARATOR.$base_template.
                                              DIRECTORY_SEPARATOR.'style.css');

          $stylesheet = file_get_contents($path_css);

          $pdf->WriteHTML($stylesheet, HTMLParserMode::HEADER_CSS);
          $pdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

          if(config('tenant.pdf_template_footer')) {
              $html_footer = $template->pdfFooter($base_template);
              $pdf->SetHTMLFooter($html_footer);
          }

          $this->uploadFile('pedido', $pdf->output('', 'S'), 'pedido');
    }

    public function uploadFile($filename, $file_content, $file_type)
    {
        $this->uploadStorage($filename, $file_content, $file_type);
    }

}
