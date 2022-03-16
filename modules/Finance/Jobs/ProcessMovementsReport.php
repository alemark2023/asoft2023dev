<?php

namespace Modules\Finance\Jobs;

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
use App\Models\Tenant\Cash;
use Modules\Finance\Traits\FinanceTrait;
use Modules\Finance\Models\GlobalPayment;


class ProcessMovementsReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, StorageDocument, FinanceTrait;

    public $params;
    public $tray_id;
    public $website_id;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Object $params, int $tray_id, int $website_id) {
        $this->params = $params;
        $this->tray_id = $tray_id;
        $this->website_id = $website_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::debug("ProcessMovementsReport Start WebsiteId => " . $this->website_id);

        $website = Website::find($this->website_id);
        $tenancy = app(Environment::class);
        $tenancy->tenant($website);


        try {

            $records = $this->getRecords($this->params ,GlobalPayment::class);
            $records->orderBy('id');
    
            Log::debug($records);
            
            Log::debug($records->get());
           

        } catch (\Exception $e) {
            Log::debug("ProcessMovementsReport Error transaction ". $e);
        }

        Log::debug("ProcessMovementsReport Finish transaction");
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


    private function getRecords($request, $model){

        $data_of_period = $this->getDatesOfPeriod((array)$request);
        //$payment_type = $request->payment_type;
        //$destination_type = $request['destination_type'];
        $last_cash_opening = $request->last_cash_opening;

        $params = (object)[
            'date_start' => $data_of_period['d_start'],
            'date_end' => $data_of_period['d_end'],
        ];

        $records = $model::whereFilterPaymentType($params);

        if($last_cash_opening == 'true'){

            $cash =  Cash::where([['user_id',auth()->user()->id],['state',true]])->first();

            if($cash){

                return $records->whereDestinationType(Cash::class)
                                ->where('destination_id', $cash->id)->latest();
            }
        }
        return $records->latest();
    }
}
