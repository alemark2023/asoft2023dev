<?php

    namespace Modules\Report\Jobs;


    use Illuminate\Bus\Queueable;
    use Illuminate\Queue\SerializesModels;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Foundation\Bus\Dispatchable;
    use Hyn\Tenancy\Models\Website;
    use Illuminate\Support\Facades\Log;
    use Exception;
    use App\Models\Tenant\DownloadTray;
    use Hyn\Tenancy\Environment;
    use App\CoreFacturalo\Helpers\Storage\StorageDocument;
    use App\Models\Tenant\Company;
    use App\Models\Tenant\Establishment;
    use Modules\Inventory\Exports\InventoryExport;
    use Barryvdh\DomPDF\Facade as PDF;
    use Modules\Inventory\Models\ItemWarehouse;
    use Mpdf\HTMLParserMode;
    use Mpdf\Mpdf;
    use Mpdf\Config\ConfigVariables;
    use Mpdf\Config\FontVariables;

    use Modules\Report\Exports\DocumentExport;

    class ProcessDocumentReport implements ShouldQueue
    {
        use Dispatchable;
        use InteractsWithQueue;
        use Queueable;
        use SerializesModels;
        use StorageDocument;

        public $tray_id;
        public $params;
        public $columns;
        public $records;
        public $company;
        public $establishment;
        public $filters;
        public $categories;
        public $categories_services;


        /**
         * Create a new job instance.
         *
         * @return void
         */
        public function __construct( int $tray_id,   $params, $company, $establishment, $filters, $categories=null, $categories_services=null,$columns)
        {
            //$this->website_id = $website_id;
            $this->tray_id = $tray_id;
            //$this->warehouse_id = $warehouse_id;
            //$this->filter = $filter;
            $this->params = $params;
            $this->establishment = $establishment;
            $this->filters = $filters;
            $this->categories = $categories;
            $this->categories_services = $categories_services;
            $this->company = $company;
            $this->columns = $columns;
        }

        /**
         * Execute the job.
         *
         * @return void
         */
        public function handle()
        {
            Log::debug("ProcessDocumentReport Start");

            //$tray = DownloadTray::find($this->tray_id);
            $path = null;
            
            try {

                $tray = DownloadTray::find($this->tray_id);

                //ini_set('max_execution_time', 0);

                //$records = $this->getRecordsTranform($this->warehouse_id, $this->filter);

                $format = $tray->format;

                if ($format === 'pdf') {

                    ini_set("pcre.backtrack_limit", "50000000");

                    Log::debug("Render pdf init");

                    $records=$this->params;
                    $company=$this->company;
                    $establishment=$this->establishment;
                    $filters=$this->filters;
                    $columns=$this->columns;

                    $html = view('report::documents.report_pdf', compact("records", "company", "establishment", "filters","columns"))->render();

                    $html = htmlspecialchars_decode($html);

                    ////////////////////////////////

                    $base_template = $establishment->template_pdf;


                    $defaultConfig = (new ConfigVariables())->getDefaults();
                    $fontDirs = $defaultConfig['fontDir'];
    
                    $defaultFontConfig = (new FontVariables())->getDefaults();
                    $fontData = $defaultFontConfig['fontdata'];

                    $pdf_font_regular = config('tenant.pdf_name_regular');
                    $pdf_font_bold = config('tenant.pdf_name_bold');
    
                    $pdf = new Mpdf([
                        'format' => 'A4-L',
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

                    $path_css = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.
                                            DIRECTORY_SEPARATOR.'pdf'.
                                            DIRECTORY_SEPARATOR.'default'.
                                            DIRECTORY_SEPARATOR.'style.css');
                    
                    $stylesheet = file_get_contents($path_css);
                    
                    $pdf->WriteHTML($stylesheet, HTMLParserMode::HEADER_CSS);

                    $pdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

        
                    $filename = 'DOCUMENT_ReporteDoc_' . date('YmdHis') . '-' . $tray->user_id;
                    Log::debug("Render pdf finish");
                    Log::debug("Html".$html);
                    Log::debug("Upload pdf init");

                    
                    $this->uploadStorage($filename, $pdf->output('', 'S'), 'download_tray_pdf');
                    Log::debug("Upload pdf finish");
                    
                    $tray->file_name = $filename;
                    $path = 'download_tray_pdf';
                    

                } else {

                    Log::debug($this->params);
                    $filename = 'DOCUMENT_ReporteDoc_' . date('YmdHis') . '-' . $tray->user_id;
                    Log::debug("Render excel init");
                    $inventoryExport = new DocumentExport();
                    $inventoryExport
                        ->records($this->params)
                        ->company($this->company)
                        ->establishment($this->establishment)
                        ->filters($this->filters)
                        ->categories($this->categories)
                        ->categories_services($this->categories_services)
                        ->columns($this->columns);
                    Log::debug("Render excel finish");

                    Log::debug("Upload excel init");

                    $inventoryExport->store(DIRECTORY_SEPARATOR . "download_tray_xlsx" . DIRECTORY_SEPARATOR . $filename . '.xlsx', 'tenant');

                    Log::debug("Upload excel finish");
                    $tray->file_name = $filename;
                    $path = 'download_tray_xlsx';
                }

                $tray->date_end = date('Y-m-d H:i:s');
                $tray->status = 'FINISHED';
                $tray->path = $path;
                $tray->save();

            } catch (Exception $e) {
                Log::debug("ProcessDocumentReport Error transaction" . $e);
            }

            Log::debug("ProcessDocumentReport Finish transaction");
        }

        
        /**
         * The job failed to process.
         *
         * @param Exception $exception
         *
         * @return void
         */
        public function failed(Exception $exception)
        {
            Log::error($exception->getMessage());
        }
    }
