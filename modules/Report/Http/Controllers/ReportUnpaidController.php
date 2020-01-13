<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Modules\Report\Exports\NoPaidExport;
use Illuminate\Http\Request;
use App\Models\Tenant\Company;
use Carbon\Carbon;
use Modules\Report\Http\Resources\QuotationCollection;
use Modules\Dashboard\Helpers\DashboardView;


class ReportUnpaidController extends Controller
{

    public function excel(Request $request) {

        $company = Company::first();
        return (new NoPaidExport)
                ->company($company)
                ->records((new DashboardView())->getUnpaid($request->all()))
                ->download('Reporte_Cuentas_Por_Cobrar'.Carbon::now().'.xlsx');

    }
}
