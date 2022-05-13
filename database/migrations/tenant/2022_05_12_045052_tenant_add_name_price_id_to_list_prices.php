<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddNamePriceIdToListPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('list_prices', function (Blueprint $table) {
            $table->unsignedInteger('name_price_id')->nullable()->after('id');
            $table->foreign('name_price_id')->references('id')->on('name_prices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('list_prices', function (Blueprint $table) {
            $table->dropForeign(['name_price_id']);
            $table->dropColumn('name_price_id'); 
        });
    }
}
