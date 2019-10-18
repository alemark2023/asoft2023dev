<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DispatchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');

        return [
            'unit_type_id' => [
                'required',
            ],
            'transfer_reason_description' => [
                'required',
            ],
            'observations' => [
                'required',
            ],
            'delivery.address'=> [
                'required',
               
            ],
            'dispatcher.identity_document_type_id'=> [
                'required',
            ],
            'dispatcher.number'=> [
                'required',
            ],
            'dispatcher.name'=> [
                'required',
            ],
            'driver.identity_document_type_id'=> [
                'required',
            ],
            'driver.number'=> [
                'required',
            ],
            'license_plate'=> [
                'required',
            ],

            
           
        ];
    }

    public function messages()
    {
        return [
            'unit_type_id' => 'El campo Dirección de llegada es obligatorio.',
            
        ];
    }
}