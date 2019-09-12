<?php

namespace Modules\Report\Traits;

use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Establishment; 
use Carbon\Carbon;

/**
 * 
 */
trait ReportTrait
{
    
    
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

        if($document_type_id && $establishment_id){

            $data = $model::where([['establishment_id', $establishment_id],['document_type_id', $document_type_id]])
                                ->whereBetween('date_of_issue', [$date_start, $date_end])->latest();

        }elseif($document_type_id){
            
            $data = $model::whereBetween('date_of_issue', [$date_start, $date_end])->latest()
                                ->where('document_type_id', 'like', '%' . $document_type_id . '%');

        }elseif($establishment_id){
            
            $data = $model::whereBetween('date_of_issue', [$date_start, $date_end])->latest()
                                ->where('establishment_id', 'like', '%' . $establishment_id . '%');

        }else{
            $data = $model::whereBetween('date_of_issue', [$date_start, $date_end])->latest();
        }
       
        return $data;
        
    }

}
