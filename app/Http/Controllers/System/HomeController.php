<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\System\Client;

class HomeController extends Controller
{
    public function index()
    {
        $clients = Client::get();

        return view('system.dashboard')->with('clients', count($clients));
    }
}
