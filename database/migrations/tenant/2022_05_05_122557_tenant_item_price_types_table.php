<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantItemPriceTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_price_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->string('unit_type_id');
            $table->decimal('quantity_unit',12,4);
            $table->decimal('price1', 12, 4);
            $table->decimal('price2', 12, 4);
            $table->decimal('price3', 12, 4);
            $table->decimal('price4', 12, 2);
            $table->boolean('price_default')->default(2);
            $table->foreign('unit_type_id')->references('id')->on('cat_unit_types');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_price_types');
    }
}
