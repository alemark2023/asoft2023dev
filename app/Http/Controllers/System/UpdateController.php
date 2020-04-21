<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Storage;

class UpdateController extends Controller
{
    public function index()
    {
        return view('system.update.index');
    }

    public function branch()
    {
        $process = new Process('git rev-parse --abbrev-ref HEAD');
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $output = $process->getOutput();
        return json_encode($output);
    }

    public function pull($branch)
    {
        $process = new Process('git pull origin '.$branch);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $output = $process->getOutput();
        return json_encode($output);
    }

    public function artisanMigrate()
    {
        $process = new Process('php ..\artisan migrate');
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $output = $process->getOutput();
        return json_encode($output);
    }

    public function artisanTenancyMigrate()
    {
        $process = new Process('php ..\artisan tenancy:migrate');
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $output = $process->getOutput();
        return json_encode($output);
    }

    public function artisanClear()
    {
        $process = new Process('php ..\artisan config:cache');
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $output = $process->getOutput();
        return json_encode($output);
    }

    public function composerInstall()
    {
        $process = new Process(system('composer dumpautoload -d '. base_path()));
        $process->run();
        // if (!$process->isSuccessful()) {
        //     throw new ProcessFailedException($process);
        // }
        $output = $process->getOutput();
        return json_encode($output);
    }
}
