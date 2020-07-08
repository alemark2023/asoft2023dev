<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artisan;

class BackupController extends Controller
{
    public function index() {

        return view('system.backup.index');
    }

    public function db()
    {
        $output = Artisan::call('bk:bd');
        return json_encode($output);
    }

    public function files()
    {
        $output = Artisan::call('bk:files');
        return json_encode($output);
    }
}
