<?php
namespace Modules\Report\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;


class ReportController extends Controller
{

    public function listReports()
    {
        return view('report::system.list_reports');
    }

}
