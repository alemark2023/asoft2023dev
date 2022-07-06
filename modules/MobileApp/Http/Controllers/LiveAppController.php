<?php

namespace Modules\MobileApp\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class LiveAppController extends Controller
{
    public function index()
    {
        return view('mobileapp::index');
    }

    public function configuration()
    {
        return view('mobileapp::configuration.index');
    }
}
