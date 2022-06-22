<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddColumnsToQuotationItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->string('name')->nullable()->after('item');
            $table->string('unit_type_id', 3)->nullable()->after('name');
            $table->decimal('factor_discount',20,8);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('unit_type_id');
            $table->dropColumn('factor_discount');
        });
    }
}
