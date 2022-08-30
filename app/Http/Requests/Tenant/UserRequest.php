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
        $password_rules = [
            'min:6',
            'confirmed',
        ];

        if($this->input('config_regex_password_user') ?? false)
        {
            $password_rules = array_merge($password_rules, [
                'regex:/[a-z]/',      
                'regex:/[A-Z]/',   
                'regex:/[0-9]/',
                'regex:/[@.$!%*#?&-]/',
            ]);
        }

        return [
            'name' => [
                'required'
            ],
            'email' => [
                'required'
            ],
            'type' => [
                'required'
            ],
            'password' => $password_rules
        ];
    }
}