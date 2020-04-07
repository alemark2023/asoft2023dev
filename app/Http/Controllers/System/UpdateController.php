<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

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
}
