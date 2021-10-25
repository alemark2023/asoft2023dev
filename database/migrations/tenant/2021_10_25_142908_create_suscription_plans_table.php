<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateSuscriptionPlansTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('cat_periods', function (Blueprint $table) {
                $table->increments('id');
                $table->char('period', 1)->comment('Define si es dia, mes o año - D/M/Y');
                $table->text('name')->comment('Nombre del periodo');
                $table->tinyInteger('active')->default(0)->comment('Si esta activo');
                $table->timestamps();

            });
            Schema::create('suscription_plans', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('cat_period_id')->default(0)->nullable()->comment('Relacion con el periodo de tiempo');
                $table->text('name')->comment('Nombre del plan');
                $table->longText('description')->comment('Descripcion del plan');
                $table->float('total', 12, 2)->default(0)->nullable()->comment('El total del costo del plan');
                $table->timestamps();
            });
            Schema::create('item_rel_suscription_plans', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('item_id')->default(0)->nullable()->comment('Relacion con items');
                $table->unsignedInteger('suscription_plan_id')->default(0)->nullable()->comment('Relacion con planes de suscripcion');
                $table->timestamps();
            });
            Schema::create('user_rel_suscription_plans', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id')->default(0)->nullable()->comment('Relacion con usuario');
                $table->unsignedInteger('suscription_plan_id')->default(0)->nullable()->comment('Relacion con planes de suscripcion');
                $table->unsignedInteger('cat_period_id')->default(0)->nullable()->comment('Relacion con el periodo de tiempo');
                $table->longText('items_text')->comment('implode de los items al momento de crearse');
                $table->json('items')->comment('Pega los items relacionados a modo de standar');
                $table->tinyInteger('editable')->default(0)->comment('Si ya ha sido adquirido, no puede ser modificado');
                $table->tinyInteger('deletable')->default(0)->comment('Si ya ha sido adquirido, no puede ser borrado');
                $table->date('start_date')->nullable()->comment('Fecha de inicio');
                $table->timestamps();
            });
            $catPeriods = [];
            $catPeriods[] = [
                'Id' => count($catPeriods) + 1,
                'period' => 'D',
                'name' => 'Diario',
                'active' => 1,
            ];

            $catPeriods[] = [
                'Id' => count($catPeriods) + 1,
                'period' => 'M',
                'name' => 'Mensual',
                'active' => 1,
            ];
            $idyear = count($catPeriods) + 1;

            $catPeriods[] = [
                'Id' => $idyear,
                'period' => 'Y',
                'name' => 'Anual',
                'active' => 1,
            ];

            DB::table('cat_periods')->insert($catPeriods);
            $suscriptionPlans = [];

            $suscriptionPlans[] = [
                'Id' => count($suscriptionPlans) + 1,
                'cat_period_id' => $idyear,
                'name' => 'Matricula Escolar',
                'description' => 'Demostración de matricula escolar',
                'total' => 1,
            ];
            DB::table('suscription_plans')->insert($suscriptionPlans);


        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('cat_periods');
            Schema::dropIfExists('suscription_plans');
            Schema::dropIfExists('item_rel_suscription_plans');
            Schema::dropIfExists('user_rel_suscription_plans');
        }
    }
