<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class AccountsReceivable implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
    /**/
    	$clients = DB::connection('tenant')
                            ->table('documents')
                            ->join('persons', 'documents.customer_id', '=', 'persons.id')
                            ->join('companies', 'documents.user_id', '=', 'companies.id')
                            ->select('companies.trade_name','companies.number','date_of_issue', 'time_of_issue','filename','persons.name', 'total_value','total', DB::raw('CONCAT(documents.series, "-", documents.number) AS full_number'))
                            ->where('total_canceled', 0)->get();
        return view('tenant.reports.no_paid.reportall_excel', ['records' => $clients]);
    }

}
