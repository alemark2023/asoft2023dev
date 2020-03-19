<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Tenant\Catalogs\DocumentType;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Company;
use Carbon\Carbon;
use Modules\Report\Http\Resources\SaleConsolidatedCollection;
use Modules\Report\Traits\ReportTrait;
use App\Models\Tenant\SaleNoteItem;
use App\Models\Tenant\DocumentItem;
use Illuminate\Support\Facades\DB;


class ReportSaleConsolidatedController extends Controller
{
    use ReportTrait;

    public function filter() {


        $persons = $this->getPersons('customers'); 
        $date_range_types = $this->getDateRangeTypes(true);
        $order_state_types = [];
        $sellers = $this->getSellers();
        $document_types = $this->getCIDocumentTypes();

        return compact('persons', 'date_range_types', 'order_state_types', 'sellers', 'document_types');
    }


    public function index() {

        return view('report::sales_consolidated.index');
    }

    public function records(Request $request)
    {
        $records = $this->getRecordsSalesConsolidated($request->all());

        return new SaleConsolidatedCollection($records->paginate(config('tenant.items_per_page')));
    }



    public function getRecordsSalesConsolidated($request){

        // dd($request);
        $records = $this->dataSalesConsolidated($request);

        return $records;

    }


    private function dataSalesConsolidated($request)
    {

        $document_type_id = $request['document_type_id']; 

        switch ($document_type_id) {

            case '01':
            case '03':
                $data = DocumentItem::whereDefaultDocumentType($request)->whereDocumentTypeId($document_type_id);
                break;

            case '80':
                $data = SaleNoteItem::whereDefaultDocumentType($request);
                break;
    
            default: 
                $document_items = DocumentItem::whereDefaultDocumentType($request);
                $sale_note_items = SaleNoteItem::whereDefaultDocumentType($request);
                $data = $document_items->union($sale_note_items);

                break;
        }

        return $data;

    }


    public function pdf(Request $request) {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;
        $records = $this->getRecordsSalesConsolidated($request->all())->get();
        $params = $request->all();

        $pdf = PDF::loadView('report::sales_consolidated.report_pdf', compact("records", "company", "establishment", "params"));

        $filename = 'Reporte_Consolidado_Items_Ventas_'.date('YmdHis');

        return $pdf->download($filename.'.pdf');
    }



}
