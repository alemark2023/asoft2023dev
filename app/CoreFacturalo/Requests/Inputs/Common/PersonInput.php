<?php

namespace App\CoreFacturalo\Requests\Inputs\Common;

use App\Models\Tenant\Person as PersonModel;

class PersonInput
{
    public static function set($person_id)
    {
        $person = PersonModel::find($person_id);

        if(!$person) {
            return null;
        }

        return [
            'identity_document_type_id' => $person->identity_document_type_id,
            'identity_document_type' => [
                'id' => $person->identity_document_type_id,
                'description' => $person->identity_document_type->description,
            ],
            'number' => $person->number,
            'name' => $person->name,
            'trade_name' => $person->trade_name,
            'country_id' => $person->country_id,
            'country' => [
                'id' => $person->country_id,
                'description' => optional($person->country)->description,
            ],
            'department_id' => $person->department_id,
            'department' => [
                'id' => $person->department_id,
                'description' => optional($person->department)->description,
            ],
            'province_id' => $person->province_id,
            'province' => [
                'id' => $person->province_id,
                'description' => optional($person->province)->description,
            ],
            'district_id' => $person->district_id,
            'district' => [
                'id' => $person->district_id,
                'description' => optional($person->district)->description,
            ],
            'address' => $person->address,
            'email' => $person->email,
            'telephone' => $person->telephone,
        ];
    }
}