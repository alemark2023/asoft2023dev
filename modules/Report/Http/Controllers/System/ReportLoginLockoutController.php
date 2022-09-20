<?php
namespace Modules\Report\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;


class ReportLoginLockoutController extends Controller
{

    public function index() 
    {
        return view('report::system.system_activity_logs.login_lockout.index');
    }
   
    public function records(Request $request)
    {
        $records = $this->getRecordsCash($request->all());
        return new CashCollection($records->paginate(config('tenant.items_per_page')));
    }

 

    public function pdf(Request $request) {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment; 
        $records = $this->getRecords($request->all(), Document::class)->get();
        
        $pdf = PDF::loadView('report::documents.report_pdf', compact("records", "company", "establishment"));

        $filename = 'Reporte_Ventas_'.date('YmdHis');
        
        return $pdf->download($filename.'.pdf');
    }
    
  
    

    public function excel(Request $request) {
    
        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment; 
        
        $records = $this->getRecords($request->all(), Document::class)->get();

        return (new DocumentExport)
                ->records($records)
                ->company($company)
                ->establishment($establishment)
                ->download('Reporte_Ventas_'.Carbon::now().'.xlsx');

    }

}
