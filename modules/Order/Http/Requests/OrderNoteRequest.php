<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class OrderNoteRequest
 *
 * @package Modules\Order\Http\Requests
 * @mixin FormRequest
 */
class OrderNoteRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'customer_id' => [
                'required',
            ],
            'exchange_rate_sale' => [
                'required',
                'numeric',
                'min:0.01'
            ],
            'currency_type_id' => [
                'required',
            ],
            'date_of_issue' => [
                'required',
            ],

            'additional_data' => 'nullable|array',
            'additional_data.*.title' => 'required',
            'additional_data.*.description' => 'required',
        ];
    }
}
