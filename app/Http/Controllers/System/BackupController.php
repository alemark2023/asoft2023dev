<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artisan;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Config;
use Anchu\Ftp\Facades\Ftp;

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

    public function upload()
    {
        Config::set('ftp.connections.connection1', array(
           'host'   => 'ftp.facturaloperu.com',
           'port' => 21,
           'username' => 'pro3@facturaloperu.com',
           'password'   => 'N22-R-.5HBMy',
           'passive'   => false,
        ));

        $fileTo = 'bk.txt';
        $fileFrom = storage_path('backups/bk.txt');
        $upload = Ftp::connection()->uploadFile($fileFrom, $fileTo, FTP_BINARY);


        $fileRemote = '/archivo.txt';
        $fileLocal = storage_path('backups/archivo.txt');
        $download = Ftp::connection()->downloadFile($fileRemote, $fileLocal, FTP_BINARY);

        dd($upload);
    }
}
