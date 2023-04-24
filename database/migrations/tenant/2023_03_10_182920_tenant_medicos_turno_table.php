<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantMedicosTurnoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicos_turno', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('turno_id');
            $table->char('user_created', 9)->nullable();
            $table->char('user_updated', 9)->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->foreign('turno_id')->references('id')->on('turnos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medicos_turno');
    }
}
