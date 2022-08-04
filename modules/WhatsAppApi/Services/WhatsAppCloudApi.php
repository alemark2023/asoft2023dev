<?php

namespace Modules\WhatsAppApi\Services;

use Modules\WhatsAppApi\Helpers\HttpConnectionApi;
use Exception;


class WhatsAppCloudApi
{

    private const GRAPH_URL = 'https://graph.facebook.com/';
    protected $api_version;
    protected $phone_number_id;
    protected $token;
    protected $base_url;
    protected $http_connection;

    
    /**
     * @return void
     */
    public function __construct()
    {
        $this->api_version = config('whatsappapi.whatsapp_cloud_api_version');
        $this->phone_number_id = 'xxxxxxxx5';
        $this->token = 'xxxx';
        $this->base_url = self::GRAPH_URL.$this->api_version.'/'.$this->phone_number_id.'/';
        $this->http_connection = new HttpConnectionApi($this->token);
    }
    
    
    /**
     * 
     * Enviar mensaje
     *
     * @param  array $params_data
     * @param  string $prefix_number
     * @return array
     * 
     */
    public function sendMessage($params_data, $prefix_number = '51')
    {
        try
        {
            $params = $this->getGeneralParams($params_data, $prefix_number);

            $response = $this->http_connection->sendRequest("{$this->base_url}messages", $params, 'POST');

            return $this->processResponse($response);
            
        } 
        catch(Exception $e) 
        {
            return $this->http_connection->responseError($e);
        }
    }
    

    /**
     * 
     * Procesar respuesta / Validar errores
     *
     * @param  array $response
     * @return array
     */
    private function processResponse($response)
    {
        $error_data = $response['error'] ?? null;

        if($error_data)
        {
            $error_message = $error_data['message'] ?? '';
            
            return $this->http_connection->responseMessage(false, 'Ocurrió un problema al enviar el mensaje: '.$error_message);
        }

        $messages = $response['messages'] ?? [];

        if(count($messages) > 0)
        {
            return $this->http_connection->responseMessage(true, 'Mensaje enviado correctamente.');
        }

        return $this->http_connection->responseMessage(false, 'Ocurrió un problema al enviar el mensaje');
    }

    
    /**
     * 
     * Obtener parámetros
     *
     * @param  array $params
     * @param  string $prefix_number
     * @return array
     */
    private function getGeneralParams($params, $prefix_number)
    {
        $type = $params['send_type'];

        $data = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $prefix_number.$params['phone_number'],
            'type' => $type
        ];

        if($type === 'document')
        {
            $data['document'] = $params['document'];
        }
        else
        {
            $data['text'] = [
                'preview_url' => false,
                'body' => $params['message']
            ];
        }
        
        return $data;
    }
    
}