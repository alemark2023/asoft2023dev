<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Company;
use App\Models\Tenant\Item;
use Modules\Inventory\Models\ItemWarehouse;
use Modules\Inventory\Exports\InventoryExport;

use Carbon\Carbon;

class ReportInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $reports = ItemWarehouse::with(['item'=>function($q){
                                    $q->where('item_type_id', '01');
                                }])->latest()->get();
                    
        return view('inventory::reports.inventory.index', compact('reports'));
    }
    
    /**
     * Search
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
        
        $reports = ItemWarehouse::with(['item'=>function($q){
            $q->where('item_type_id', '01');
        }])->latest()->get();
        
        return view('inventory::reports.inventory.index', compact('reports'));
    }
    
    /**
     * PDF
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function pdf(Request $request) {

        $company = Company::first();
        $establishment = Establishment::first();
        
        $reports = ItemWarehouse::with(['item'=>function($q){
            $q->where('item_type_id', '01');
        }])->latest()->get();
        
        $pdf = PDF::loadView('inventory::reports.inventory.report_pdf', compact("reports", "company", "establishment"));
        $filename = 'Reporte_Inventario'.date('YmdHis');
        
        return $pdf->download($filename.'.pdf');
    }
    
    /**
     * Excel
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function excel(Request $request) {
        $company = Company::first();
        $establishment = Establishment::first();
       
        $records = ItemWarehouse::with(['item'=>function($q){
            $q->where('item_type_id', '01');
        }])->latest()->get();

        return (new InventoryExport)
            ->records($records)
            ->company($company)
            ->establishment($establishment)
            ->download('ReporteInv'.Carbon::now().'.xlsx');
    }
}
