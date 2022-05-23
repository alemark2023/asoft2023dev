<?php

namespace Modules\Item\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemPriceTypeRequest extends FormRequest
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
            ]
        ];

    }
}
