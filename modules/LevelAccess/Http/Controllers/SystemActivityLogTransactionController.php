<?php

namespace Modules\LevelAccess\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\LevelAccess\Models\SystemActivityLog;
use Modules\LevelAccess\Http\Resources\{
    SystemActivityTransactionCollection
};
use Illuminate\Support\Facades\DB;
use App\Traits\ElectronicDocumentTrait;
use App\Models\Tenant\{
    Document,
    Dispatch,
    Perception,
    PurchaseSettlement,
    Retention,
    Voided,
    Summary,
};


class SystemActivityLogTransactionController extends Controller
{

    use ElectronicDocumentTrait;
    

    public function index()
    {
        return view('levelaccess::system_activity_logs.transactions.index');
    }


    public function columns()
    {
        return [
            'date_of_issue' => 'Fecha emisión',
            'time_of_issue' => 'Hora emisión',
        ];
    }

    
    /**
     * 
     * Actividades del sistema - transacciones
     * 
     *
     * @param  Request $request
     * @return SystemActivityTransactionCollection
     */
    public function records(Request $request)
    {
        $documents = $this->getQuerySystemActivityLogTransaction('documents', $request);
        $dispatches = $this->getQuerySystemActivityLogTransaction('dispatches', $request);
        $perceptions = $this->getQuerySystemActivityLogTransaction('perceptions', $request);
        $purchase_settlements = $this->getQuerySystemActivityLogTransaction('purchase_settlements', $request);
        $retentions = $this->getQuerySystemActivityLogTransaction('retentions', $request);

        $summaries = $this->getQuerySystemActivityLogTransactionGroup('summaries', 'RC', $request);
        $summary_voided = $this->getQuerySystemActivityLogTransactionGroup('summaries', 'RC', $request, true);
        $voided = $this->getQuerySystemActivityLogTransactionGroup('voided', 'RA', $request);


        $records = $documents->union($dispatches)
                            ->union($perceptions)->union($purchase_settlements)
                            ->union($retentions)->union($summaries)
                            ->union($summary_voided)->union($voided);

        return new SystemActivityTransactionCollection($records->orderBy('date_of_issue', 'desc')->orderBy('time_of_issue', 'desc')->paginate(config('tenant.items_per_page')));
    }


}
