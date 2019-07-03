<?php

namespace Modules\Dashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Dashboard\Helpers\DashboardData;
use Modules\Dashboard\Helpers\DashboardView;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard::index');
    }

    public function filter()
    {
        return [
            'establishments' => DashboardView::getEstablishments()
        ];
    }

    public function data(Request $request)
    {
        return [
            'data' => (new DashboardData())->data($request->all()),
        ];
    }

    public function unpaid(Request $request)
    {
        return [
            'records' => (new DashboardView())->getUnpaid($request->all())
       ];
    }
}
