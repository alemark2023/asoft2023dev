<?php

namespace App\CoreFacturalo\Requests\Api\Transform;

use App\CoreFacturalo\Requests\Api\Transform\Common\EstablishmentTransform;
use App\CoreFacturalo\Requests\Api\Transform\Common\PersonTransform;
use App\CoreFacturalo\Requests\Api\Transform\Common\LegendTransform;
use App\CoreFacturalo\Requests\Api\Transform\Common\ActionTransform;

class DispatchTransform
{
    public static function transform($inputs)
    {
        return  [
            'series' => Functions::valueKeyInArray($inputs, 'serie_documento'),
            'number' => Functions::valueKeyInArray($inputs, 'numero_documento'),
            'date_of_issue' => Functions::valueKeyInArray($inputs, 'fecha_de_emision'),
            'time_of_issue' => Functions::valueKeyInArray($inputs, 'hora_de_emision'),
            'document_type_id' => Functions::valueKeyInArray($inputs, 'codigo_tipo_documento'),
            'establishment' => EstablishmentTransform::transform($inputs['datos_del_emisor']),
            'customer' => PersonTransform::transform($inputs['datos_del_cliente_o_receptor']),
            'observations' => Functions::valueKeyInArray($inputs, 'observaciones'),
            'transport_mode_type_id' => Functions::valueKeyInArray($inputs, 'codigo_modo_transporte'),
            'transfer_reason_type_id' => Functions::valueKeyInArray($inputs, 'codigo_motivo_traslado'),
            'transfer_reason_description' => Functions::valueKeyInArray($inputs, 'descripcion_motivo_traslado'),
            'date_of_shipping' => Functions::valueKeyInArray($inputs, 'fecha_de_traslado'),
            'transshipment_indicator' => Functions::valueKeyInArray($inputs, 'indicador_de_transbordo'),
            'port_code' => Functions::valueKeyInArray($inputs, 'codigo_de_puerto'),
            'unit_type_id' => Functions::valueKeyInArray($inputs, 'unidad_peso_total'),
            'total_weight' => Functions::valueKeyInArray($inputs, 'peso_total'),
            'packages_number' => Functions::valueKeyInArray($inputs, 'numero_de_bultos'),
            'container_number' => Functions::valueKeyInArray($inputs, 'numero_de_contenedor'),
            'license_plate' => Functions::valueKeyInArray($inputs, 'numero_de_placa'),
            'origin' => self::origin($inputs),
            'delivery' => self::delivery($inputs),
            'dispatcher' => self::dispatcher($inputs),
            'driver' => self::driver($inputs),
            'items' => self::items($inputs),
            'legends' => LegendTransform::transform($inputs),
            'actions' => ActionTransform::transform($inputs),

        ];
    }

    private static function origin($inputs)
    {
        if(key_exists('direccion_partida', $inputs)) {
            $origin = $inputs['direccion_partida'];

            return [
                'location_id' => $origin['ubigeo'],
                'address' => $origin['direccion'],
            ];
        }
        return null;
    }

    private static function delivery($inputs)
    {
        if(key_exists('direccion_llegada', $inputs)) {
            $delivery = $inputs['direccion_llegada'];

            return [
                'location_id' => $delivery['ubigeo'],
                'address' => $delivery['direccion'],
            ];
        }
        return null;
    }

    private static function dispatcher($inputs)
    {
        if(key_exists('transportista', $inputs)) {
            $dispatcher = $inputs['transportista'];

            return [
                'identity_document_type_id' => $dispatcher['codigo_tipo_documento_identidad'],
                'number' => $dispatcher['numero_documento'],
                'name' => $dispatcher['apellidos_y_nombres_o_razon_social'],
            ];
        }
        return null;
    }

    private static function driver($inputs)
    {
        $driver = null;
        if(key_exists('chofer', $inputs)) {
            $driver = $inputs['chofer'];

            return [
                'identity_document_type_id' => $driver['codigo_tipo_documento_identidad'],
                'number' => $driver['numero_documento']
            ];
        }
        return null;
    }

    private static function items($inputs)
    {
        if(key_exists('items', $inputs)) {
            $items = [];
            foreach ($inputs['items'] as $row) {
                $items[] = [
                    'internal_id' => $row['codigo_interno'],
                    'quantity' => Functions::valueKeyInArray($row, 'cantidad'),
                ];
            }

            return $items;
        }
        return null;
    }
}
