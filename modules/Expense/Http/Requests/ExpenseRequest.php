<?php

namespace Modules\Expense\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExpenseRequest extends FormRequest
{
     
    public function authorize()
    {
        return true; 
    }
 
    public function rules()
    { 
        
        return [
            'supplier_id' => [
                'required',
            ],
            'expense_reason_id' => [
                'required',
            ],
            'number' => [
                'required',
                'numeric'
            ], 
            'date_of_issue' => [
                'required',
            ], 
        ];
    }
}
