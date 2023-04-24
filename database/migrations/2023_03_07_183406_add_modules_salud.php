<?php

use App\Models\System\Module;
use App\Models\System\ModuleLevel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModulesSalud extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('modules', function (Blueprint $table) {
            $e = new Module([
                                'value'       => 'salud',
                                'description' => 'Salud',
                                'sort'        => 13
                            ]);
            $e->save();
            // $e->setLastSortInt()->push();

            ModuleLevel::query()->insert([
                [
                    'value'       => 'atenciones',
                    'description' => 'Atenciones',
                    'module_id'   => $e->id
                ],
                [
                    'value'       => 'citas',
                    'description' => 'Citas',
                    'module_id'   => $e->id
                ],
                [
                    'value'       => 'pacientes',
                    'description' => 'Pacientes',
                    'module_id'   => $e->id
                ],
                [
                    'value'       => 'medicos',
                    'description' => 'Medicos',
                    'module_id'   => $e->id
                ],
                [
                    'value'       => 'especialidades',
                    'description' => 'Especialidades',
                    'module_id'   => $e->id
                ],
                [
                    'value'       => 'turnos',
                    'description' => 'Turnos',
                    'module_id'   => $e->id
                ],
                [
                    'value'       => 'horarios',
                    'description' => 'Horarios',
                    'module_id'   => $e->id
                ]
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $e = Module::where(['description' => 'Salud','value' => 'salud',])->first();

        if (!empty($e)) {
            $id = $e->id;
            $e->delete();
            $q = ModuleLevel::where('module_id', $id)->get();
            foreach ($q as $i) {
                $i->delete();
            }
        }
    }
}
