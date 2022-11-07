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
    use Modules\Inventory\Exports\InventoryExport;
    use Barryvdh\DomPDF\Facade as PDF;
    use Modules\Inventory\Models\ItemWarehouse;
    use Mpdf\HTMLParserMode;
    use Mpdf\Mpdf;
    use Mpdf\Config\ConfigVariables;
    use Mpdf\Config\FontVariables;
    use Modules\Report\Exports\DocumentExport;
    use App\Traits\JobReportTrait;
    use Modules\Report\Http\Controllers\ReportDocumentController;
    

    class ProcessDocumentReport implements ShouldQueue
    {
        use Dispatchable;
        use InteractsWithQueue;
        use Queueable;
        use SerializesModels;
        use JobReportTrait;
        use StorageDocument;

        public $tray_id;
        public $columns;
        public $filters;
        public $website_id;


        /**
         * Create a new job instance.
         *
         * @return void
         */
        public function __construct(int $tray_id, int $website_id, array $filters, $columns)
        {
            $this->website_id = $website_id;
            $this->tray_id = $tray_id;
            $this->filters = $filters;
            $this->columns = $columns;
        }


        /**
         * Execute the job.
         *
         * @return void
         */
        public function handle()
        {
            $this->showLogInfo("ProcessDocumentReport Start WebsiteId => {$this->website_id}");

            $website = $this->findWebsite($this->website_id);
            $tenancy = app(Environment::class);
            $tenancy->tenant($website);
            $path = null;
            
            try 
            {
                $tray = $this->findDownloadTray($this->tray_id);
                \Log::info("tra aq.".$tray->module);

                $company = $this->getDataCompany();
                $establishment = $this->getDataEstablishment($this->filters['establishment_id']);
                
                $records = app(ReportDocumentController::class)->getRecords($this->filters, $this->filters['class_type_records']);

                //get categories
                $categories = [];
                $categories_services = [];

                if($this->filters['include_categories'] == "true")
                {
                    $categories = app(ReportDocumentController::class)->getCategories($records, false);
                    $categories_services = app(ReportDocumentController::class)->getCategories($records, true);
                }


                $format = $tray->format;

                if ($format === 'pdf') {

                    ini_set("pcre.backtrack_limit", "50000000");

                    Log::debug("Render pdf init");

                    $filters=$this->filters;
                    $columns=$this->columns;

                    Log::debug("Render columns columns");

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

                    Log::debug($records);
                    $filename = 'DOCUMENT_ReporteDoc_' . date('YmdHis') . '-' . $tray->user_id;
                    Log::debug("Render excel init");
                    $inventoryExport = new DocumentExport();
                    $inventoryExport
                        ->records($records)
                        ->company($company)
                        ->establishment($establishment)
                        ->filters($this->filters)
                        ->categories($categories)
                        ->categories_services($categories_services)
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

            } 
            catch(Exception $e) 
            {
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
