<?php
namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;


class ConfigurationController extends Controller
{

    public function index()
    {
        return view('system.configuration.index');
    }
}
