<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Tenant\Catalogs\DocumentType;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Modules\Report\Exports\CommercialAnalysisExport;
use Illuminate\Http\Request;
use Modules\Report\Traits\ReportTrait;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Document;
use App\Models\Tenant\Person;
use App\Models\Tenant\Company;
use Carbon\Carbon;
use Modules\Report\Http\Resources\CommercialAnalysisCollection;

class ReportCommercialAnalysisController extends Controller
{
   
     
    public function filter() {

        $document_types = [];

        $establishments = Establishment::all()->transform(function($row) {
            return [
                'id' => $row->id,
                'name' => $row->description
            ];
        });
        
        return compact('document_types','establishments');
    }
      

    public function index() {
       
        return view('report::commercial_analysis.index');
    }
   
    public function records(Request $request)
    {
        $records = $this->getRecords($request->all(), Person::class);

        return new CommercialAnalysisCollection($records->paginate(config('tenant.items_per_page')));
    }

    
    public function getRecords($request, $model){

        // dd($request['period']);
        $document_type_id = $request['document_type_id'];
        $establishment_id = $request['establishment_id'];
        $period = $request['period'];
        $date_start = $request['date_start'];
        $date_end = $request['date_end'];
        $month_start = $request['month_start'];
        $month_end = $request['month_end'];

        $d_start = null;
        $d_end = null;

        switch ($period) {
            case 'month':
                $d_start = Carbon::parse($month_start.'-01')->format('Y-m-d');
                $d_end = Carbon::parse($month_start.'-01')->endOfMonth()->format('Y-m-d');
                // $d_end = Carbon::parse($month_end.'-01')->endOfMonth()->format('Y-m-d');
                break;
            case 'between_months':
                $d_start = Carbon::parse($month_start.'-01')->format('Y-m-d');
                $d_end = Carbon::parse($month_end.'-01')->endOfMonth()->format('Y-m-d');
                break;
            case 'date':
                $d_start = $date_start;
                $d_end = $date_start;
                // $d_end = $date_end;
                break;
            case 'between_dates':
                $d_start = $date_start;
                $d_end = $date_end;
                break;
        }
 
        $records = $this->data($document_type_id, $establishment_id, $d_start, $d_end, $model);

        return $records;

    }


    private function data($document_type_id, $establishment_id, $date_start, $date_end, $model)
    {

        // if($document_type_id && $establishment_id){

        //     $data = $model::where([['establishment_id', $establishment_id],['document_type_id', $document_type_id]])
        //                         ->whereBetween('date_of_issue', [$date_start, $date_end])->latest();

        // }elseif($document_type_id){
            
        //     $data = $model::whereBetween('date_of_issue', [$date_start, $date_end])->latest()
        //                         ->where('document_type_id', 'like', '%' . $document_type_id . '%');

        // }elseif($establishment_id){
            
        //     $data = $model::whereBetween('date_of_issue', [$date_start, $date_end])->latest()
        //                         ->where('establishment_id', 'like', '%' . $establishment_id . '%');

        // }else{
            // $data = $model::whereBetween('date_of_issue', [$date_start, $date_end])->latest();
        // }
        $data = $model::whereType('customers')->latest();
       
        return $data;
        
    }
  

    public function excel(Request $request) {
    
        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment; 
        
        $records = $this->getRecords($request->all(), Person::class)->get();

        return (new CommercialAnalysisExport)
                ->records($records)
                ->company($company)
                ->download('Reporte_Analisis_comercial_'.Carbon::now().'.xlsx');
    }

}
