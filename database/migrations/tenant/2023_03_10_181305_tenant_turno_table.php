<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class TenantTurnoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turnos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description', 25);
            $table->char('user_created', 9)->nullable();
            $table->char('user_updated', 9)->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        DB::table('turnos')->insert([ 
            ['description' => 'Mañana'],
            ['description' => 'Tarde'],
            ['description' => 'Noche'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('turnos');
    }
}
