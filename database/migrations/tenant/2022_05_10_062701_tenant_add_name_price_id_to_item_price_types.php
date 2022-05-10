<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddNamePriceIdToItemPriceTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_price_types', function (Blueprint $table) {
            $table->unsignedInteger('name_price_id')->nullable()->after('description');
            $table->foreign('name_price_id')->references('id')->on('name_price_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_price_types', function (Blueprint $table) {
            $table->dropForeign(['name_price_id']);
            $table->dropColumn('name_price_id');  
        });
    }
}
