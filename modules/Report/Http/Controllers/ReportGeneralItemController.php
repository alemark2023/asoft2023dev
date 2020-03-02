<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Tenant\Catalogs\DocumentType;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Modules\Report\Exports\GeneralItemExport;
use Illuminate\Http\Request;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Document;
use App\Models\Tenant\DocumentItem;
use App\Models\Tenant\Company;
use Carbon\Carbon;
use Modules\Report\Http\Resources\GeneralItemCollection;
use Modules\Report\Traits\ReportTrait;


class ReportGeneralItemController extends Controller
{
    use ReportTrait;

    public function filter() {

        $document_types = DocumentType::whereIn('id', ['01', '03'])->get();

        return compact('document_types');
    }


    public function index() {

        return view('report::general_items.index');
    }


    public function records(Request $request)
    {
        $records = $this->getRecordsItems($request->all(), DocumentItem::class);

        return new GeneralItemCollection($records->paginate(config('tenant.items_per_page')));
    }



    public function getRecordsItems($request, $model){

        $data_of_period = $this->getDataOfPeriod($request);
        $document_type_id = $request['document_type_id'];
        $d_start = $data_of_period['d_start'];
        $d_end = $data_of_period['d_end'];

        $records = $this->dataItems($d_start, $d_end, $document_type_id, $model);

        return $records;

    }


    private function dataItems($date_start, $date_end, $document_type_id, $model)
    {

        $document_types = $document_type_id ? [$document_type_id] : ['01','03'];

        $data = $model::whereHas('document', function($query) use($date_start, $date_end, $document_types){
                            $query
                            ->whereBetween('date_of_issue', [$date_start, $date_end])
                            ->whereIn('document_type_id', $document_types)
                            ->latest()
                            ->whereTypeUser();
                        });

        return $data;

    }



    public function excel(Request $request) {

        $records = $this->getRecordsItems($request->all(), DocumentItem::class)->get();
        $type = 'Ventas_';

        return (new GeneralItemExport)
                ->records($records)
                ->download('Reporte_General_Productos_'.$type.Carbon::now().'.xlsx');

    }
}
