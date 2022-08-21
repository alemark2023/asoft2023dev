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
    use Modules\Report\Http\Controllers\ReportDocumentController;
    use App\Models\Tenant\Catalogs\DocumentType;
    use Modules\Report\Traits\ReportTrait;
    use Modules\Report\Exports\DocumentExport;

    class ProcessDocumentReport implements ShouldQueue
    {
        use Dispatchable;
        use InteractsWithQueue;
        use Queueable;
        use SerializesModels;
        use StorageDocument;
        use ReportTrait;

        public $tray_id;
        public $params;
        public $columns;


        /**
         * Create a new job instance.
         *
         * @return void
         */
        public function __construct( int $tray_id,   array $params, $columns)
        {
            //$this->website_id = $website_id;
            $this->tray_id = $tray_id;
            //$this->warehouse_id = $warehouse_id;
            //$this->filter = $filter;
            $this->params = $params;
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
            Log::debug("fdgfgfd");
            $tray = DownloadTray::find($this->tray_id);
            $path = null;

            $tray_id = $this->tray_id;
            
            if (empty($tray)) {

                \Log::debug("No hay datos
                    $ tray_id       =>" . var_export($tray_id, true) . "
                    ");

            } else {
                try {
                    $columns=$this->columns;
                    //dd($columns->guides);
                    $company = Company::first();
                    $establishment = ($this->params->establishment_id) ? Establishment::findOrFail($this->params->establishment_id) : auth()->user()->establishment;

                    $documentTypeId = "01";
                    if ($request->has('document_type_id')) {
                        $documentTypeId = str_replace('"', '', $request->document_type_id);
                    }
                    $documentType = DocumentType::find($documentTypeId);
                    if (null === $documentType) {
                        $documentType = new DocumentType();
                    }

                    $classType = $documentType->getCurrentRelatiomClass();

                    $records = $this->getRecords($request->all(), $classType);
                    $records= $records->get();
                    $filters = $request->all();
                    //ini_set('max_execution_time', 0);

                    //$records = $this->getRecordsTranform($this->warehouse_id, $this->filter);

                    if (!is_object($tray)) {
                        //Log::debug('DE ' . var_export($tray, true));
                    }
                    $format = $tray->format;

                    if ($format === 'pdf') {

                        ini_set("pcre.backtrack_limit", "50000000");

                        Log::debug("Render pdf init");
                        
                        $html = view('report::documents.report_pdf', compact("records", "company", "establishment", "filters","columns"))->render();

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

                        Log::debug("Upload pdf init");

                      
                        $this->uploadStorage($filename, $pdf->output('', 'S'), 'download_tray_pdf');
                        Log::debug("Upload pdf finish");
                        
                        $tray->file_name = $filename;
                        $path = 'download_tray_pdf';
                        

                    } else {

                        $categories = [];
                        $categories_services = [];

                        if($request->include_categories == "true"){
                            $categories = ReportDocumentController::getCategories($records, false);
                            $categories_services = ReportDocumentController::getCategories($records, true);
                        }

                        Log::debug($records);
                        $filename = 'DOCUMENT_ReporteDoc_' . date('YmdHis') . '-' . $tray->user_id;
                        Log::debug("Render excel init");
                        $inventoryExport = new DocumentExport();
                        $inventoryExport
                            ->records($records)
                            ->company($company)
                            ->establishment($establishment)
                            ->filters($filters)
                            ->categories($categories)
                            ->categories_services($categories_services)
                            ->columns($columns);
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
