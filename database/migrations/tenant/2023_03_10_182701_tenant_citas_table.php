<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('especialidad_id');
            $table->unsignedInteger('medico_id');
            $table->unsignedInteger('customer_id');
            $table->date('fec_atencion');
            $table->time('ini_atencion');
            $table->time('fin_atencion');
            $table->string('estado', 50);
            $table->string('obs', 500)->nullable();
            $table->char('user_created', 9)->nullable();
            $table->char('user_updated', 9)->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->foreign('especialidad_id')->references('id')->on('especialidad');
            $table->foreign('medico_id')->references('id')->on('medicos');
            $table->foreign('customer_id')->references('id')->on('persons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('citas');
    }
}
