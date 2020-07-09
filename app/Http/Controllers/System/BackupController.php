<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artisan;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupController extends Controller
{
    public function index() {

        $avail = new Process('df -m -h --output=avail /');
        $avail->run();
        $disc_used = $avail->getOutput();

        $df = new Process('du -sh '.storage_path().' | cut -f1');
        $df->run();
        $storage_size = $df->getOutput();

        return view('system.backup.index')->with('disc_used', $disc_used)->with('storage_size', $storage_size);
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
