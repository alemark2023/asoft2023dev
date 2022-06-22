<?php

use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\AttributeType;
use App\Models\Tenant\Catalogs\ChargeDiscountType;
use App\Models\Tenant\Catalogs\Country;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\Department;
use App\Models\Tenant\Catalogs\District;
use App\Models\Tenant\Catalogs\IdentityDocumentType;
use App\Models\Tenant\Catalogs\OperationType;
use App\Models\Tenant\Catalogs\SystemIscType;
use Illuminate\Support\Facades\Cache;

if (!function_exists('func_get_operation_types')) {
    function func_get_operation_types()
    {
        if (Cache::has('operation_types')) {
            return Cache::get('operation_types');
        }

        $operation_types = OperationType::query()
            ->where('active', true)
            ->get();

        Cache::put('operation_types', $operation_types, 1440);

        return $operation_types;
    }
}

if (!function_exists('func_get_affectation_igv_types')) {
    function func_get_affectation_igv_types()
    {
        if (Cache::has('affectation_igv_types')) {
            return Cache::get('affectation_igv_types');
        }

        $affectation_igv_types = AffectationIgvType::query()
            ->where('active', true)
            ->get();

        Cache::put('affectation_igv_types', $affectation_igv_types, 1440);

        return $affectation_igv_types;
    }
}

if (!function_exists('func_get_system_isc_types')) {
    function func_get_system_isc_types()
    {
        if (Cache::has('system_isc_types')) {
            return Cache::get('system_isc_types');
        }

        $system_isc_types = SystemIscType::query()
            ->where('active', true)
            ->get();

        Cache::put('system_isc_types', $system_isc_types, 1440);

        return $system_isc_types;
    }
}

if (!function_exists('func_get_identity_document_types')) {
    function func_get_identity_document_types()
    {
        if (Cache::has('identity_document_types')) {
            return Cache::get('identity_document_types');
        }

        $identity_document_types = IdentityDocumentType::query()
            ->where('active', true)
            ->get();

        Cache::put('identity_document_types', $identity_document_types, 1440);

        return $identity_document_types;
    }
}

if (!function_exists('func_get_currency_types')) {
    function func_get_currency_types()
    {
        if (Cache::has('currency_types')) {
            return Cache::get('currency_types');
        }

        $currency_types = CurrencyType::query()
            ->where('active', true)
            ->get();

        Cache::put('currency_types', $currency_types, 1440);

        return $currency_types;
    }
}

if (!function_exists('func_get_discount_types')) {
    function func_get_discount_types()
    {
        if (Cache::has('discount_types')) {
            return Cache::get('discount_types');
        }

        $discount_types = ChargeDiscountType::query()
            ->where('type', 'discount')
            ->where('level', 'item')
            ->where('id', '00')
            ->get();

        Cache::put('discount_types', $discount_types, 1440);

        return $discount_types;
    }
}

if (!function_exists('func_get_charge_types')) {
    function func_get_charge_types()
    {
        if (Cache::has('charge_types')) {
            return Cache::get('charge_types');
        }

        $charge_types = ChargeDiscountType::query()
            ->where('type', 'charge')
            ->where('level', 'item')
            ->get();

        Cache::put('charge_types', $charge_types, 1440);

        return $charge_types;
    }
}

if (!function_exists('func_get_attribute_types')) {
    function func_get_attribute_types()
    {
        if (Cache::has('attribute_types')) {
            return Cache::get('attribute_types');
        }

        $attribute_types = AttributeType::query()
            ->where('active', true)
            ->get();

        Cache::put('attribute_types', $attribute_types, 1440);

        return $attribute_types;
    }
}

if (!function_exists('func_get_countries')) {
    function func_get_countries()
    {
        if (Cache::has('countries')) {
            return Cache::get('countries');
        }

        $countries = Country::query()
            ->get();

        Cache::put('countries', $countries, 1440);

        return $countries;
    }
}

if (!function_exists('func_get_departments')) {
    function func_get_departments()
    {
        if (Cache::has('departments')) {
            return Cache::get('departments');
        }

        $departments = Department::query()
            ->with('provinces', 'provinces.districts')
            ->get()->transform(function ($row) {
                return [
                    'id' => $row->id,
                    'description' => func_str_to_lower_utf8($row->description),
                    'provinces' => $row->provinces->transform(function ($p) {
                        return [
                            'id' => $p->id,
                            'description' => func_str_to_lower_utf8($p->description),
                            'districts' => $p->districts->transform(function ($d) {
                                return [
                                    'id' => $d->id,
                                    'description' => func_str_to_lower_utf8($d->description)
                                ];
                            }),
                        ];
                    }),
                ];
            });

        Cache::put('departments', $departments, 1440);

        return $departments;
    }
}

if (!function_exists('func_get_locations')) {
    function func_get_locations()
    {
        if (Cache::has('locations')) {
            return Cache::get('locations');
        }

        $locations = [];
        $departments = Department::query()
            ->with('provinces', 'provinces.districts')
            ->get();
        foreach ($departments as $department) {
            $children_provinces = [];
            foreach ($department->provinces as $province) {
                $children_districts = [];
                foreach ($province->districts as $district) {
                    $children_districts[] = [
                        'value' => $district->id,
                        'label' => func_str_to_lower_utf8($district->id . " - " . $district->description)
                    ];
                }
                $children_provinces[] = [
                    'value' => $province->id,
                    'label' => func_str_to_lower_utf8($province->description),
                    'children' => $children_districts
                ];
            }
            $locations[] = [
                'value' => $department->id,
                'label' => func_str_to_lower_utf8($department->description),
                'children' => $children_provinces
            ];
        }

        Cache::put('locations', $locations, 1440);

        return $locations;
    }
}

if (!function_exists('func_get_full_address')) {
    function func_get_full_address($row)
    {
        if ($row->address === '' || is_null($row->district_id)) {
            return '';
        }
        $location = District::query()
            ->with('province', 'province.department')
            ->where('id', $row->district_id)->first();
        if ($location) {
            $department_name = func_str_to_upper_utf8($location->province->department->description);
            $province_name = func_str_to_upper_utf8($location->province->description);
            $district_name = func_str_to_upper_utf8($location->description);
            return "{$row->address}, {$department_name} - {$province_name} - {$district_name}";
        }
        return '';
    }
}

if (!function_exists('func_str_to_upper_utf8')) {
    function func_str_to_upper_utf8($text)
    {
        if (is_null($text)) {
            return null;
        }
        return mb_strtoupper($text, 'utf-8');
    }
}

if (!function_exists('func_str_to_lower_utf8')) {
    function func_str_to_lower_utf8($text)
    {
        if (is_null($text)) {
            return null;
        }
        return mb_strtolower($text, 'utf-8');
    }
}
