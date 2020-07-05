<?php
namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Models\System\Configuration;



class ConfigurationController extends Controller
{

    public function index()
    {
        return view('system.configuration.index');
    }

    public function record()
    {

        $configuration = Configuration::first();

        return [
            'token_public_culqui' => $configuration->token_public_culqui,
            'token_private_culqui' => $configuration->token_private_culqui,
        ];
    }


    public function store(Request $request)
    {
        $configuration = Configuration::first();

        if($request->token_public_culqui)
        {
            $configuration->token_public_culqui = $request->token_public_culqui;
        }

        if($request->token_private_culqui)
        {
            $configuration->token_private_culqui = $request->token_private_culqui;
        }

        $configuration->save();

        return [
            'success' => true,
            'message' => 'Datos guardados con exito'
        ];


    }
}
