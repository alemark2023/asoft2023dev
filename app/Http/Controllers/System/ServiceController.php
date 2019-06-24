<?php
namespace App\Http\Controllers\System;

use App\CoreFacturalo\Services\Ruc\Sunat;
use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    public function ruc($number)
    {
        $service = new Sunat();
        $res = $service->get($number);
        if ($res) {
            return [
                'success' => true,
                'data' => [
                    'name' => $res->razonSocial,
                    'trade_name' => $res->nombreComercial,
                ]
            ];
        } else {
            return [
                'success' => false,
                'message' => $service->getError()
            ];
        }
    }
}