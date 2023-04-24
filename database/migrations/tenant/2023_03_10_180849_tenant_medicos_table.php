<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantMedicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('apellidos', 50);
            $table->char('dni', 8);
            $table->string('direccion')->nullable();
            $table->string('email', 50)->nullable();
            $table->string('phone', 25)->nullable();
            $table->char('sexo', 1);
            $table->string('num_colegiatura', 20);
            $table->date('fec_nacimiento')->nullable();
            $table->char('user_created', 9)->nullable();
            $table->char('user_updated', 9)->nullable();
            $table->boolean('enabled')->default(true);
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
        Schema::dropIfExists('medicos');
    }
}
