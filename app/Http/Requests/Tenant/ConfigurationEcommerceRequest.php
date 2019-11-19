<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConfigurationEcommerceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        return [
            'information_contact_email' => [
                'required',
            ],
            'information_contact_name' => [
                'required',
            ],
            'information_contact_phone' => [
                'required',
            ],
        ];
    }
}
