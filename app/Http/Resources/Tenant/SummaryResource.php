<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class SummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        $response_message = null;
        $response_type = null;

        if($this->soap_shipping_response){
            if($this->soap_shipping_response->sent){

                $response_message = $this->soap_shipping_response->description;
                $status_code =  $this->soap_shipping_response->status_code;

                switch ($status_code) {
                    case 0:
                        $response_type = 'success';
                        break;
                    case 99:
                        $response_type = 'error';
                        break;
                    default:
                        $response_type = 'error';
                        break;
                }
 
            }

        }

        return [
            'id' => $this->id,
            'identifier' => $this->identifier,
            'date_of_issue' => $this->date_of_issue->format('Y-m-d'),
            'response_message' => $response_message,
            'response_type' => $response_type,
            'download_cdr' => $this->download_external_cdr,
            'unknown_error_status_response' => $this->unknown_error_status_response,
            'manually_regularized' => $this->manually_regularized,
            'error_manually_regularized' => $this->error_manually_regularized,

            'send_to_pse' => $this->send_to_pse,
            'response_signature_pse' => optional($this->response_signature_pse)->message,
            'response_send_cdr_pse' => optional($this->response_send_cdr_pse)->message,
        ];
    }
}