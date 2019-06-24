<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BankAccountRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        return [
            'bank_id' => [
                'required',
            ],
            'description' => [
                'required',
            ],
            'number' => [
                'required',
            ],
            'currency_type_id' => [
                'required',
            ],
        ];
    }
}