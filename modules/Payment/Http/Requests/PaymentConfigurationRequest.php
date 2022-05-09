<?php

namespace Modules\Payment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentConfigurationRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        
        $type = $this->input('type');

        if($type == 'yape' && $this->input('enabled_yape'))
        {

            return [
                'enabled_yape' => [
                    'required',
                ],
                'qrcode_yape' => [
                    'required',
                ],
                'name_yape' => [
                    'required',
                ],
                'telephone_yape' => [
                    'required',
                    'numeric',
                    'digits:9',
                ],
            ];

        }

        return [];
    }

}