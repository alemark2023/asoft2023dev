<?php

namespace App\CoreFacturalo\Helpers\Functions;

use App\Models\Tenant\Catalogs\District;


class SetDataHelper
{
    
    /**
     * 
     * Validar y registrar distrito
     *
     * @param  string $district_id
     * @param  string $description
     * @return void
     */
    public static function createDistrict($district_id, $description)
    {

        $district = District::find($district_id);

        if(!$district)
        {
            $province_id = substr($district_id, 0 ,4);

            District::insert([
                'id' => $district_id,
                'province_id' => $province_id,
                'description' => $description,
                'active' => true,
            ]);
        }

    }
    

    /**
     * 
     * Eliminar distrito
     *
     * @param  string $district_id
     * @return void
     */
    public static function deleteDistrict($district_id)
    {
        District::where('id', $district_id)->delete();
    }

}
