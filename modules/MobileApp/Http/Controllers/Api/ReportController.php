<?php

namespace Modules\MobileApp\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Dashboard\Helpers\DashboardData;
use App\CoreFacturalo\Helpers\Functions\FunctionsHelper;
use Modules\MobileApp\Http\Requests\Api\ReportGeneralSaleRequest;


class ReportController extends Controller
{

    
    /**
     * 
     * Reporte general de ventas
     *
     * @param  Request $request
     * @return array
     */
    public function reportGeneralSale(ReportGeneralSaleRequest $request)
    {

        $establishment_id = auth()->user()->establishment_id;
        $period = $request['period'];
        $month_start = $request['month_start'];
        $month_end = $request['month_end'];
        $d_start = null;
        $d_end = null;

        FunctionsHelper::setDateInPeriod($request, $d_start, $d_end);

        return [
            'data' => (new DashboardData())->getGeneralTotals($establishment_id, $d_start, $d_end, $period, $month_start, $month_end)
        ];

    }

    
}
