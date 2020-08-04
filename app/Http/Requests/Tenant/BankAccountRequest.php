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
            'initial_balance' => [
                'required',
                'numeric',
                'min:0'
            ],
        ];
    }

    public function messages()
    {
        return [
            // 'initial_balance.gte' => 'El saldo inicial debe ser mayor o igual que 0.',
        ];
    }

}