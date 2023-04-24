<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Tenant\Catalogs\DocumentType;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Modules\Report\Exports\DocumentExport;
use Illuminate\Http\Request;
use Modules\Report\Traits\ReportTrait;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\User;
use App\Models\Tenant\Document;
use App\Models\Tenant\Company;
use App\Models\Tenant\PaymentMethodType;
use Carbon\Carbon;
use Modules\Report\Http\Resources\CashCollection;
use App\Models\Tenant\Cash;
use App\Models\Tenant\Configuration;
 

class ReportIncomeSummaryController extends Controller
{
     
    /**
     * 
     * Usado en:
     * CashController - App
     * 
     * @param  int $cash_id
     * 
     */ 
    public function pdf($cash_id) {

        $company = Company::active();
        $cash = Cash::findOrFail($cash_id);
        $order_cash_income = Configuration::getOrderCashIncome();

        set_time_limit(0); 
        $pdf = PDF::loadView('report::income_summary.report_pdf', compact("cash", "company", 'order_cash_income'));

        $filename = "Reporte_Resúmen_Ingreso - {$cash->user->name} - {$cash->date_opening} {$cash->time_opening}";
        
        return $pdf->download($filename.'.pdf');
    }
  
     
}
