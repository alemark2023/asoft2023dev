<?php

namespace App\CoreFacturalo\Requests\Inputs\Common;

use App\CoreFacturalo\Helpers\Number\NumberLetter;

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

        if(array_key_exists('total', $inputs)) {
            $legends[] = [
                'code' => 1000,
                'value' => NumberLetter::convertToLetter($inputs['total'])
            ];
        }

        return $legends;
    }
}