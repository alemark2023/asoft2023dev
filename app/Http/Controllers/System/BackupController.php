<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\System\Client;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use DateTime;
use Artisan;
use Config;
use Exception;
use App\Traits\BackupTrait;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Models\Website;

class BackupController extends Controller
{

    use BackupTrait;

    public function index() {

        $avail = new Process('df -m -h --output=avail /');
        $avail->run();
        $disc_used = $avail->getOutput();

        $df = new Process('du -sh '.storage_path().' | cut -f1');
        $df->run();
        $storage_size = $df->getOutput();

        $most_recent = $this->mostRecent();

        $clients = Client::without(['hostname','plan'])
            ->select('hostname_id', 'name')
            ->get();

        return view('system.backup.index')->with('disc_used', $disc_used)->with('storage_size', $storage_size)->with('last_zip', $most_recent)->with('clients', $clients);
    }

    public function db(Request $request)
    {
        $request->validate([
            'type' => 'required|in:individual,todos',
            'hostname_id' => 'nullable|required_if:type,individual',
        ]);

        $database = '';
        if ($request->type === 'individual') {
            $hostname = Hostname::findOrFail($request->hostname_id);
            $website = Website::findOrFail($hostname->website_id);
            $database = $website->uuid;
        }
        $output = Artisan::call('bk:bd', [
            'type' => $request->type,
            'database' => $database,
        ]);
        return json_encode($output);
    }

    public function files(Request $request)
    {
        $request->validate([
            'type' => 'required|in:individual,todos',
            'hostname_id' => 'nullable|required_if:type,individual',
        ]);

        $folder = '';
        if ($request->type === 'individual') {
            $hostname = Hostname::findOrFail($request->hostname_id);
            $website = Website::findOrFail($hostname->website_id);
            $folder = $website->uuid;
        }
        $output = Artisan::call('bk:files', [
            'type' => $request->type,
            'folder' => $folder,
        ]);
        return json_encode($output);
    }

    public function upload(Request $request)
    {

        $config = [
            'driver' => 'ftp',
            'host'   => $request['host'],
            'port' => $request['port'],
            'username' => $request['username'],
            'password'   => $request['password'],
            'port'  => 21,
            'passive'   => true,
        ];

        Config::set('filesystems.disks.ftp', $config);

        // definimos y subimos el archivo
        try {

            $most_recent = $this->mostRecent();

            $fileTo = $most_recent['name'];
            // $fileFrom = storage_path('app/'.$most_recent['path']);

            $fileFrom = Storage::get($most_recent['path']);

            $upload = Storage::disk('ftp')->put($fileTo, $fileFrom);

            return [
                'success' => $upload,
                'message' => 'Proceso finalizado satisfactoriamente'
            ];


        } catch (Exception $e) {

            $this->setErrorLog($e);
            return $this->getErrorMessage("Lo sentimos, ocurrió un error inesperado: {$e->getMessage()}");

        }

    }

    public function mostRecent()
    {
        $zips = Storage::allFiles('backups/zip/');

        if (count($zips) > 0) {
            $name_zips = [];
            $most_recent_time = '';

            foreach($zips as $zip){
                $zip_explode = explode( '/', $zip);
                if(count($zip_explode) <= 3){
                    array_push($name_zips, $zip_explode[2]);
                    $last = Storage::lastModified($zip);
                    $datetime = new DateTime("@$last");
                    if ($datetime > $most_recent_time) {
                        $most_recent_time = $datetime;
                        $most_recent_path = $zip;
                        $most_recent_name = $zip_explode[2];
                    }
                }
            }

            return [
                'path' => $most_recent_path,
                'name' => $most_recent_name
            ];
        } else {
            return '';
        }
    }


    public function download($filename)
    {

        return Storage::download('backups'.DIRECTORY_SEPARATOR.'zip'.DIRECTORY_SEPARATOR.$filename);

    }

}
