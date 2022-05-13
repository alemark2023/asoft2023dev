<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantNamePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('name_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->string('unit_type_id');
            $table->decimal('quantity_unit',12,4);
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
        Schema::dropIfExists('name_prices');
    }
}
