<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Exports\AccountsReceivable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Dashboard\Helpers\DashboardData;
use Modules\Dashboard\Helpers\DashboardUtility;
use Modules\Dashboard\Helpers\DashboardSalePurchase;
use Modules\Dashboard\Helpers\DashboardView;
use Modules\Dashboard\Helpers\DashboardStock;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\Document;


class DashboardController extends Controller
{
    public function index()
    {
        if(auth()->user()->type != 'admin' || !auth()->user()->searchModule('dashboard'))
            return redirect()->route('tenant.documents.index');

        return view('dashboard::index');
    }

    public function filter()
    {
        return [
            'establishments' => DashboardView::getEstablishments()
        ];
    }

    public function data(Request $request)
    {
        return [
            'data' => (new DashboardData())->data($request->all()),
        ];
    }

    public function unpaid(Request $request)
    {
        return [
            'records' => (new DashboardView())->getUnpaid($request->all())
       ];
    }

    public function unpaidall()
    {
        $document_payments = DB::table('document_payments')
            ->select('document_id', DB::raw('SUM(payment) as total_payment'))
            ->groupBy('document_id');
        return dd(DB::connection('tenant')
                            ->table('documents')
                            ->join('persons', 'documents.customer_id', '=', 'persons.id')
                            ->join('companies', 'documents.user_id', '=', 'companies.id')
                            ->leftJoinSub($document_payments, 'payments', function ($join) {
                            $join->on('documents.id', '=', 'payments.document_id');
                            })
                            ->select('companies.trade_name','companies.number','date_of_issue', 'time_of_issue','filename','persons.name', 'total_value','total', DB::raw('CONCAT(documents.series, "-", documents.number) AS full_number','IFNULL(payments.total_payment, 0) AS total_payment'), 'total_payment')
                            ->where('total_canceled', 0)->get()
                        );
         
    }

    public function data_aditional(Request $request)
    {
        return [
            'data' => (new DashboardSalePurchase())->data($request->all()),
        ];
    }

    public function stockByProduct(Request $request)
    {
        return  (new DashboardStock())->data($request);
    }


    public function utilities(Request $request)
    {
        return [
            'data' => (new DashboardUtility())->data($request->all()),
        ];
    }

}
