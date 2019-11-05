<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePadronesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('padrones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ruc')->index();;
            $table->string('nombre_razon_social', 155);
            $table->string('estado_contribuyente');
            $table->string('condicion_domicilio', 155);
            $table->string('ubigeo');
            $table->string('tipo_via');
            $table->string('nombre_via');
            $table->string('codigo_zona', 155);
            $table->string('tipo_zona');
            $table->string('numero', 155);
            $table->string('interior');
            $table->string('lote');
            $table->string('departamento');
            $table->string('manzana');
            $table->string('kilometro');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('padrones');
    }
}
