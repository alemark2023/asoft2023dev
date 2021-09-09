<?php

namespace App\CoreFacturalo\Requests\Inputs\Common;

use App\CoreFacturalo\Helpers\Number\NumberLetter;
use App\Models\Tenant\Company;
use Modules\Document\Services\DocumentXmlService;

class LegendInput
{
    public static function set($inputs)
    {
        $legends = [];
        if(array_key_exists('legends', $inputs)) {
            if($inputs['legends']) {
                foreach ($inputs['legends'] as $row)
                {
                    $code = $row['code'];
                    $value = $row['value'];

                    $legends[] = [
                        'code' => $code,
                        'value' => $value
                    ];
                }
            }
        }
        
        if(Company::active()->operation_amazonia && in_array($inputs['document_type_id'], ['01', '03'])){

            $legends[] = [
                'code' => 2002,
                'value' => 'SERVICIOS PRESTADOS EN LA AMAZONÍA  REGIÓN SELVA PARA SER CONSUMIDOS EN LA MISMA'
            ];

            $legends[] = [
                'code' => 2001,
                'value' => 'BIENES TRANSFERIDOS EN LA AMAZONÍA REGIÓN SELVA PARA SER CONSUMIDOS EN LA MISMA'
            ];

        }

        $has_discounts_no_base = (new DocumentXmlService())->hasDiscountsNoBaseByInputs($inputs);

        if($has_discounts_no_base){

            if(array_key_exists('total_payable_amount', $inputs)) {
                $legends[] = [
                    'code' => 1000,
                    'value' => NumberLetter::convertToLetter($inputs['total_payable_amount'])
                ];
            }

        }else{

            if(array_key_exists('total', $inputs)) {
                $legends[] = [
                    'code' => 1000,
                    'value' => NumberLetter::convertToLetter($inputs['total'])
                ];
            }

        }

        return $legends;
    }
}