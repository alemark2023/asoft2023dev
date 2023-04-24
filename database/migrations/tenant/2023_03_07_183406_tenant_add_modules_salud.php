<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\CoreFacturalo\Helpers\Functions\SetDataHelper;

class TenantAddModulesSalud extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $module_data = SetDataHelper::getModuleData('salud', 'Salud', 13);
        $find_row = SetDataHelper::getModuleConnection('tenant')->where($module_data)->first();

        if (!$find_row) 
        {
            $id = SetDataHelper::getModuleConnection('tenant')->insertGetId($module_data);
            DB::table('module_levels')->insert([
                ['value' => 'atenciones', 'description' => 'Atenciones', 'module_id' => $id],
                ['value' => 'citas', 'description' => 'Citas', 'module_id' => $id],
                ['value' => 'pacientes', 'description' => 'Pacientes', 'module_id' => $id],
                ['value' => 'medicos', 'description' => 'Medicos', 'module_id' => $id],
                ['value' => 'especialidades', 'description' => 'Especialidades', 'module_id' => $id],
                ['value' => 'turnos', 'description' => 'Turnos', 'module_id' => $id],
                ['value' => 'horarios', 'description' => 'Horarios', 'module_id' => $id]
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $module_data = SetDataHelper::getModuleData('salud', 'Salud', 13);
        $find_row = SetDataHelper::getModuleConnection('tenant')->where($module_data)->first();

        if ($find_row) 
        {
            SetDataHelper::getModuleConnection('tenant')->delete($find_row->id);
            DB::table('module_levels')->where('module_id', $find_row->id)->delete();
        }
    }
}
