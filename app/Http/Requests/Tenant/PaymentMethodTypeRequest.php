<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentMethodTypeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        return [
            'id' => [
                'required',
                Rule::unique('tenant.1')->ignore($id),
            ],
            'description' => [
                'required',
            ],
            'active' => [
                'required',
            ],
        ];
    }
}
