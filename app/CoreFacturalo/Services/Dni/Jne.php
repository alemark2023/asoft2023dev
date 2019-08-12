<?php

namespace App\CoreFacturalo\Services\Dni;

use App\CoreFacturalo\Services\Helpers\Functions;
use App\CoreFacturalo\Services\Models\Person;
use GuzzleHttp\Client;

class Jne
{
    public static function search($number)
    {
        if (strlen($number) !== 8) {
            return [
                'success' => false,
                'message' => 'DNI tiene 8 digitos.'
            ];
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"http://aplicaciones007.jne.gob.pe/srop_publico/Consulta/Afiliado/GetNombresCiudadano?DNI={$number}");        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec ($ch);         
        curl_close ($ch);
 
        // $client = new  Client(['base_uri' => 'http://aplicaciones007.jne.gob.pe/']);
        // $response = $client->request('GET', 'srop_publico/Consulta/Afiliado/GetNombresCiudadano?DNI='.$number);

        // if ($response->getStatusCode() == 200 && $response != "") {
        if ($response) {
            // $text = $response->getBody()->getContents();
            $text = $response;
            $parts = explode('|', $text);
            if (count($parts) === 3) {
                $person = new Person();
                $person->number = $number;
                $person->verification_code = Functions::verificationCode($number);
                $person->name = $parts[0].' '.$parts[1].', '.$parts[2];
                $person->first_name = $parts[0];
                $person->last_name = $parts[1];
                $person->names = $parts[2];

                return [
                    'success' => true,
                    'data' => $person
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Datos no encontrados.'
                ];
            }
        }

        return [
            'success' => false,
            'message' => 'Coneccion fallida.'
        ];
    }
}