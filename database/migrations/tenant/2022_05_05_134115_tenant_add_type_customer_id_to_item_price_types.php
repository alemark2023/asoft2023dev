<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddTypeCustomerIdToItemPriceTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_price_types', function (Blueprint $table) {
            $table->unsignedInteger('type_customer_id')->nullable()->after('description');
            $table->foreign('type_customer_id')->references('id')->on('person_types');
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
            $table->dropForeign(['type_customer_id']);
            $table->dropColumn('type_customer_id');  
        });
    }
}
