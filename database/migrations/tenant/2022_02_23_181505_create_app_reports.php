<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Builder;

class CreateAppReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $reportData = self::getModuleData();
        $reportRow = self::getSystemModuleConnection()->where($reportData)->first();
        if ($reportRow === null) {
            self::getSystemModuleConnection()->insert($reportData);
        }
        $reportRow = self::getSystemModuleConnection()->where($reportData)->first();
        if ($reportRow != null) {
            $levels = $this->getModuleLevelData($reportRow->id);
            foreach ($levels as $level) {
                $reportLevelRow = self::getSystemModuleLevelConnection()->where($level)->first();
                if ($reportLevelRow === null) {
                    self::getSystemModuleLevelConnection()->insert($level);
                }
            }
        }
    }

    public static function getModuleData(): array
    {
        return [
            'value' => 'reports',
            'description' => 'Reportes',
            // 'sort' => 16,
            'order_menu' => 9,
        ];
    }

    public static function getSystemModuleConnection(): Builder
    {
        return DB::connection('tenant')->table('modules');
    }

    public function getModuleLevelData($module_id): array
    {
        if (empty($module_id)) {

            echo("No se encuentra el id de modulo\n");
        }
        $data = [];
        $data [] = [
            'value' => 'reports_app_general',
            'description' => 'General',
            'module_id' => $module_id,

        ];
        $data [] = [
            'value' => 'reports_app_shopping',
            'description' => 'Compras',
            'module_id' => $module_id,

        ];
        $data [] = [
            'value' => 'reports_app_sales',
            'description' => 'Ventas',
            'module_id' => $module_id,

        ];
        $data [] = [
            'value' => 'reports_app_sales_commissions',
            'description' => 'Ventas/Comisiones',
            'module_id' => $module_id,

        ];
        $data [] = [
            'value' => 'reports_app_sales_orders',
            'description' => 'Pedidos',
            'module_id' => $module_id,

        ];
        $data [] = [
            'value' => 'reports_app_guides',
            'description' => 'Guias',
            'module_id' => $module_id,

        ];
        /*
        $data [] = [
            'value' => 'suscription_app_payments',
            'description' => 'Pagos',
            'module_id' => $module_id,

        ];
        */
        return $data;
    }

    public static function getSystemModuleLevelConnection(): Builder
    {
        return DB::connection('tenant')->table('module_levels');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $reportData = self::getModuleData();

        $reportRow = self::getSystemModuleConnection()->where($reportData)->first();
        if ($reportRow != null) {
            $levels = $this->getModuleLevelData($reportRow->id);
            foreach ($levels as $level) {
                $reportLevelRow = self::getSystemModuleLevelConnection()->where($level)->first();
                if ($reportLevelRow != null) {

                    DB::connection('tenant')->table('module_levels')->delete($reportLevelRow->id);

                }
            }
            DB::connection('tenant')->table('modules')->delete($reportRow->id);
        }
    }
}
