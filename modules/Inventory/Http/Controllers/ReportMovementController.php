<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Modules\Inventory\Exports\ReportMovementExport;
use Illuminate\Http\Request;
use App\Models\Tenant\Company;
use Carbon\Carbon;
use Modules\Inventory\Http\Resources\ReportMovementCollection;
use Modules\Inventory\Models\Inventory;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Traits\InventoryTrait;
use Modules\Inventory\Http\Requests\ReportMovementRequest;


class ReportMovementController extends Controller
{

	use InventoryTrait;

    public function filter()
    {
		return [
			'warehouses'             => $this->optionsWarehouse(),
			'inventory_transactions' => $this->allInventoryTransaction(),
		];
    }


    public function records(ReportMovementRequest $request)
    {
        $records = $this->getRecords($request->all());

        return new ReportMovementCollection($records->paginate(config('tenant.items_per_page')));
    }


    /**
     * @param $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|Inventory
     */
    private function getRecords($request)
    {
        $warehouse_id = $request['warehouse_id'];
        $inventory_transaction_id = $request['inventory_transaction_id'];
        $date_start = $request['date_start'];
        $date_end = $request['date_end'];
        $item_id = $request['item_id'];
        $movement_type = $request['movement_type'];
        $order_inventory_transaction_id = $request['order_inventory_transaction_id'];

//        dd('aca');

        return Inventory::whereFilterReportMovement($warehouse_id, $inventory_transaction_id, $date_start, $date_end,
            $item_id, $order_inventory_transaction_id, $movement_type);

    }


    /**
     * PDF
     * @param ReportMovementRequest $request
     * @return \Illuminate\Http\Response
     */
    public function pdf(ReportMovementRequest $request)
    {
        $pdf = PDF::loadView('inventory::reports.movements.report_template', $this->getDataForFormat($request));

        return $pdf->download('Reporte_Movimientos' . date('YmdHis') . '.pdf');
    }


    /**
     * Excel
     * @param ReportMovementRequest $request
     * @return \Illuminate\Http\Response
     */
    public function excel(Request $request)
    {
        $exportData = new ReportMovementExport();
        $exportData->data($this->getDataForFormat($request));

        return $exportData->download('Reporte_Movimientos' . date('YmdHis') . '.xlsx');
    }


    /**
     * Obtener datos para generar reporte pdf/excel
     *
     * @param  mixed $request
     * @return array
     */
    private function getDataForFormat($request)
    {
        $warehouse_id = $request->input('warehouse_id', '');
        if($warehouse_id) {
            $warehouse = Warehouse::query()->select('description')->find($warehouse_id);
            $warehouse_name = $warehouse->description;
        } else {
            $warehouse_name = 'Todos';
        }
        return [
            'company' => Company::first(),
            'warehouse_name' => $warehouse_name,
            'records' => $this->getRecords($request->all())->get()->transform(function($row, $key) { return  $row->getRowResourceReport(); }),
        ];
    }

}
