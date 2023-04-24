<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\LevelAccess\Models\ModuleLevel;
use App\CoreFacturalo\Helpers\Functions\SetDataHelper;


class TenantChangeDescriptionValueToModuleLevels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        SetDataHelper::fixedModuleLevelValuedKardex('inventory_report_kardex', 'inventory_report_valued_kardex', ModuleLevel::class);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        SetDataHelper::fixedModuleLevelValuedKardex('inventory_report_valued_kardex', 'inventory_report_kardex', ModuleLevel::class);
    }


}
