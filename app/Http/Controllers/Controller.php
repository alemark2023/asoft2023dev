<?php

namespace App\Http\Controllers;

use App\Models\Tenant\Person;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Class Controller
 *
 * @package App\Http\Controllers
 * @mixin BaseController
 */
class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * Devuelve un cliente basado en el id
     * @param int $id
     *
     * @return array
     */
    public function searchClientById($id = 0){

        $customers = Person::whereType('customers')
            ->where('id',$id)
            ->get()
            ->transform(function($row) {
                /** @var Person $row */
                return  $row->getCollectionData();
            });

        return compact('customers');
    }

    /**
     * Valida si existe una conexion valida al sitio de destino, se requiere configuracion adicional para guardarlo en el archivo
     * en ese caso se debe revisar la funcion ExtraLog
     *
     * @param string $host
     * @param int    $wait_seconds
     */
    public function pingSite ($host = 'demo.facturalo.pro', $wait_seconds = 1){

        $ports = [
            'http'  => 80,
            'https' => 443,
        ];
        $string = '';
        foreach ($ports as $key => $port) {
            $fp = @fsockopen($host, $port, $errCode, $errStr, $wait_seconds);
            $string .= "<br>Ping $host:$port ($key) ==> ";
            if ($fp) {
                $string.= 'SUCCESS';
                fclose($fp);
            } else {
                $string.= "ERROR: $errCode - $errStr";
            }
            $string.= PHP_EOL;
        }
        self::ExtraLog(__FILE__."::".__LINE__." \n Validando Host $host  \n>>>>\n$string");
    }

    /**
     * Guarda un log en facturalo si la variable EXTRA_LOG es verdadera en el archivo .env
     * @param string $string
     */
    public static function ExtraLog($string = ''){
        if(\Config('extra.extra_log') === true){
            \Log::channel('facturalo')->debug(
                "\n**************************************DEBUG SE ENCUENTRA ACTIVADO**********************************************************************************\n".
                $string.
                "\n**************************************DEBUG SE ENCUENTRA ACTIVADO**********************************************************************************\n");
        }
    }
}
