<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Tenant\Catalogs\DocumentType;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Company;
use Carbon\Carbon;
use Modules\Report\Http\Resources\OrderNoteCollection;
use Modules\Report\Traits\ReportTrait;
use Modules\Order\Models\OrderNoteItem;


class ReportOrderNoteController extends Controller
{
    use ReportTrait;

    public function filter() {


        $persons = $this->getPersons('customers'); 

        return compact('persons');
    }


    public function index() {

        return view('report::order_notes.index');
    }

    public function records(Request $request)
    {
        $records = $this->getRecordsOrderNotes($request->all(), OrderNoteItem::class);

        return new OrderNoteCollection($records->paginate(config('tenant.items_per_page')));
    }



    public function getRecordsOrderNotes($request, $model){

        $delivery_date = $request['delivery_date'];
        $person_id = $request['person_id'];
 
        $records = $this->dataOrderNotes($person_id, $delivery_date, $model);

        return $records;

    }


    private function dataOrderNotes($person_id, $delivery_date, $model)
    {

        $data = $model::whereHas('order_note', function($query) use($delivery_date, $person_id){
                            $query->where('delivery_date', $delivery_date)
                                    ->where('customer_id', $person_id)
                                    ->whereTypeUser();
                        })
                        ->latest('id');

        return $data;

    }


    public function pdf(Request $request) {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;
        $records = $this->getRecordsOrderNotes($request->all(), OrderNoteItem::class)->get();

        $pdf = PDF::loadView('report::order_notes.report_pdf', compact("records", "company", "establishment"));

        $filename = 'Reporte_Pedido_'.date('YmdHis');

        return $pdf->download($filename.'.pdf');
    }


    // public function excel(Request $request) {

    //     $company = Company::first();
    //     $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;

    //     $records = $this->getRecordsCustomers($request->all(), Document::class)->get();

    //     return (new CustomerExport)
    //             ->records($records)
    //             ->company($company)
    //             ->establishment($establishment)
    //             ->download('Reporte_Ventas_por_Cliente_'.Carbon::now().'.xlsx');

    // }

}
