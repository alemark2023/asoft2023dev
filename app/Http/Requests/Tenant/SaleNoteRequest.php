<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use function Complex\sec;

/**
 * Class SaleNoteRequest
 *
 * @package App\Http\Requests\Tenant
 * @mixin FormRequest
 */
class SaleNoteRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        \Log::channel('facturalo')->error("\n".__FILE__."\n");

        \Log::channel('facturalo')->error("\n************************************************************************************************************************\n".
            json_encode($this->toArray()).
            "\n************************************************************************************************************************\n");
        return [
            'customer_id' => [
                'required',
            ],
            'exchange_rate_sale' => [
                'required',
                'numeric'
            ],
            'currency_type_id' => [
                'required',
            ],
            'date_of_issue' => [
                'required',
            ],
            'series_id' => [
                'required',
            ],
        ];
    }
}
