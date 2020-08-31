<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Tenant\Catalogs\DocumentType;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Modules\Report\Exports\DocumentHotelExport;
use Illuminate\Http\Request;
use Modules\Report\Traits\ReportTrait;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Company;
use App\Models\Tenant\{
    Document,
    SaleNote,
    Dispatch
};
use Carbon\Carbon;

class ReportMassiveDownloadController extends Controller
{
     
    use ReportTrait;

    public function index() 
    {
        return view('report::massive-downloads.index');
    }
   

    public function filter() {

        $document_types = DocumentType::whereIn('id', ['01', '03','80', '09'])->get();

        $persons = $this->getPersons('customers');

        return compact('document_types','persons');
    }


    public function records(Request $request)
    {

        $document_types = json_decode($request->document_types);
        $total_documents = 0;
        // dd($request->all());

        if(count($document_types) == 0){
            $document_types = ['all'];
        }

        foreach ($document_types as $document_type) {

            switch ($document_type) {
                case '01':
                case '03':
                    $total_documents += $this->getRecordsByModel(Document::class, $request)->whereIn('document_type_id', ['01', '03'])->count();
                    break;
                case '80':
                    $total_documents += $this->getRecordsByModel(SaleNote::class, $request)->count();
                    break;
                case '09':
                    $total_documents += $this->getRecordsByModel(Dispatch::class, $request)->count();
                    break;
                default:
                    $total_documents += $this->getRecordsByModel(Document::class, $request)->whereIn('document_type_id', ['01', '03'])->count();
                    $total_documents += $this->getRecordsByModel(SaleNote::class, $request)->count();
                    $total_documents += $this->getRecordsByModel(Dispatch::class, $request)->count();
                    break;
            }
            
        }
    
        return [
            'total' => $total_documents
        ];

    }


    public function getRecordsByModel($model, $request){

        return $model::whereBetween('date_of_issue', [$request->date_start, $request->date_end])
                        ->latest()
                        ->whereTypeUser();
    }
  

    public function getRecords($request){
 
        $date_start = $request['date_start'];
        $date_end = $request['date_end']; 
 
        $records = $this->data( $date_start, $date_end);

        return $records;

    }


    private function data($date_start, $date_end)
    {

        if($date_start && $date_end){

            $data = DocumentHotel::where([['date_entry','>=', $date_start],['date_exit','<=', $date_end]])->latest();

        }else{
            $data = DocumentHotel::latest();
        }
       
        return $data;
        
    }

}
