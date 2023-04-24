<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CurrencyTypeRequest extends FormRequest
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
                Rule::unique('tenant.cat_currency_types')->ignore($id),
            ],
            'description' => [
                'required',
            ],
            'active' => [
                'required',
            ],
            'symbol' => [
                'required',
            ],
        ];
    }
}