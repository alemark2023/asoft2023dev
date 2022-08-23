<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required'
            ],
            'email' => [
                'required',
                'email',
            ],
            'type' => [
                'required'
            ],
            'establishment_id' => [
                'required'
            ],
            'password' => [
                'min:6',
                'confirmed',
            ],

            
            'personal_email' => [
                'nullable',
                'email',
            ],
            'corporate_email' => [
                'nullable',
                'email',
            ],
            'personal_cell_phone' => [
                'nullable',
                'numeric',
            ],
            'corporate_cell_phone' => [
                'nullable',
                'numeric',
            ],
            'number' => [
                'nullable',
                'numeric',
            ],
        ];
    }
}