<?php

namespace Modules\Inventory\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Hyn\Tenancy\Models\Website;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Tenant\DownloadTray;
use Hyn\Tenancy\Environment;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\Models\Tenant\Company;
use App\Models\Tenant\Establishment;
use Modules\Inventory\Exports\InventoryExport;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Modules\Inventory\Models\ItemWarehouse;

class ProcessInventoryReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, StorageDocument;

    public $website_id;
    public $tray_id;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $website_id, int $tray_id) {
        $this->website_id = $website_id;
        $this->tray_id = $tray_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::debug("ProcessInventoryReport Start WebsiteId => " . $this->website_id);

        $website = Website::find($this->website_id);
        $tenancy = app(Environment::class);
        $tenancy->tenant($website);

        $tray = DownloadTray::find($this->tray_id);
        $path = null;

        try {
            $company = Company::query()->first();
            $establishment = Establishment::query()->first();
            //ini_set('max_execution_time', 0);

            $records = $this->getRecordsTranform(1, '01');
            $format = $tray->format;
            $totals = [
                'purchase_unit_price' => '85.000000',
                'sale_unit_price' => '140.000000',
            ];

            if ($format === 'pdf') {
                Log::debug("Render pdf init");
                $pdf = PDF::loadView('inventory::reports.inventory.report', compact('records', 'company', 'establishment', 'format', 'totals'));
                $pdf->setPaper('A4', 'landscape');
                $filename = 'INVENTORY_ReporteInv_' . date('YmdHis') . '-' . $tray->user_id;

                Log::debug("Render pdf finish");

                Log::debug("Upload pdf init");
                $this->uploadStorage($filename, $pdf->output('', 'S'), 'download_tray_pdf');
                Log::debug("Upload pdf finish");
                $tray->file_name = $filename;
                $path = 'download_tray_pdf';

            }
            else {
                $filename = 'INVENTORY_ReporteInv_' . date('YmdHis') . '-' . $tray->user_id;
                Log::debug("Render excel init");
                $inventoryExport = new InventoryExport();
                    $inventoryExport
                        ->records($records)
                        ->company($company)
                        ->establishment($establishment)
                        ->format($format)
                        ->totals($totals);
                Log::debug("Render excel finish");

                Log::debug("Upload excel init");

                    $inventoryExport->store(DIRECTORY_SEPARATOR."download_tray_xlsx".DIRECTORY_SEPARATOR . $filename.'.xlsx', 'tenant');

                Log::debug("Upload excel finish");
                $tray->file_name = $filename;
                $path = 'download_tray_xlsx';
            }
            $tray->date_end = date('Y-m-d H:i:s');
            $tray->status = 'FINISHED';
            $tray->path = $path;
            $tray->save();

        } catch (\Exception $e) {
            Log::debug("ProcessInventoryReport Error transaction". $e);
        }

        Log::debug("ProcessInventoryReport Finish transaction");
    }

     /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        Log::error($exception->getMessage());
    }

    public function getRecordsTranform($warehouse_id, $filter){
       
        Log::debug("getRecordsTranform init". date('H:i:s'));
        $records = $this->getRecords($warehouse_id);

        // $records->orderBy('items.name', 'desc');

        $data = [];

        foreach ($records->latest()->cursor() as $row) {
            $stock = $row->stock;
            $add = true;
            $item = $row->item;
            if ($filter === '02') {
                $add = ($stock < 0);
            }
            if ($filter === '03') {
                $add = ($stock == 0);
            }
            if ($filter === '04') {
                $add = ($stock > 0 && $stock <= $item->stock_min);
            }
            if ($filter === '05') {
                $add = ($stock > $item->stock_min);
            }
            if ($add) {
                $data[] = [
                    'barcode' => $item->barcode,
                    'internal_id' => $item->internal_id,
                    'name' => $item->description,
                    'item_category_name' => optional($item->category)->name,
                    'stock_min' => $item->stock_min,
                    'stock' => $stock,
                    'sale_unit_price' => $item->sale_unit_price,
                    'purchase_unit_price' => $item->purchase_unit_price,
                    'profit'=>number_format($item->sale_unit_price-$item->purchase_unit_price,2,'.',''),
                    'model' => $item->model,
                    'brand_name' => $item->brand->name,
                    'date_of_due' => optional($item->date_of_due)->format('d/m/Y'),
                    'warehouse_name' => $row->warehouse->description
                ];
            }
        }

        /*$records = $records->latest()->get()->transform(function($row) use ($filter,&$data) {

            $stock = $row->stock;
            $add = true;
            $item = $row->item;
            if ($filter === '02') {
                $add = ($stock < 0);
            }
            if ($filter === '03') {
                $add = ($stock == 0);
            }
            if ($filter === '04') {
                $add = ($stock > 0 && $stock <= $item->stock_min);
            }
            if ($filter === '05') {
                $add = ($stock > $item->stock_min);
            }
            if ($add) {
                $data[] = [
                    'barcode' => $item->barcode,
                    'internal_id' => $item->internal_id,
                    'name' => $item->description,
                    'item_category_name' => optional($item->category)->name,
                    'stock_min' => $item->stock_min,
                    'stock' => $stock,
                    'sale_unit_price' => $item->sale_unit_price,
                    'purchase_unit_price' => $item->purchase_unit_price,
                    'profit'=>number_format($item->sale_unit_price-$item->purchase_unit_price,2,'.',''),
                    'model' => $item->model,
                    //'brand' => optional($item->brand),
                    'brand_name' => $item->brand->name,
                    'date_of_due' => optional($item->date_of_due)->format('d/m/Y'),
                    'warehouse_name' => $row->warehouse->description
                ];
            }
        });*/

        Log::debug("getRecordsTranform finish". date('H:i:s'));

        return $data;
    }

    private function getRecords($warehouse_id = 0) {
        $query = ItemWarehouse::with(['item', 'item.category', 'item.brand'])
                              ->whereHas('item', function ($q) {
                                  $q->where([
                                                ['item_type_id', '01'],
                                                ['unit_type_id', '!=', 'ZZ'],
                                            ])
                                    ->whereNotIsSet();
                              });
                             
        if ($warehouse_id != 0) {
            $query->where('item_warehouse.warehouse_id', $warehouse_id);
        }
        return $query;

    }
}
