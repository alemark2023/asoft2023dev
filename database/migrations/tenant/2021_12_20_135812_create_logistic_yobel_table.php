<?php

    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class CreateLogisticYobelTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {


            Schema::create('logistic_yobel', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('person_id')->nullable()->comment('Relacion con cliente');
                $table->unsignedInteger('order_id')->nullable()->comment('Relacion con Orders');
                $table->string('order')->nullable()->comment('numero de orden de  importacion');
                $table->string('reference')->nullable()->comment('Referencia del excel');
                $table->string('gateway_code')->nullable()->comment('Codigo de pasarela');
                $table->json('items');
                $table->timestamps();

                $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            });
            Schema::create('logistic_yobel_api', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('logistic_yobel_id')->nullable()->comment('Relacion con la orden de yobel');
                $table->string('command')->nullable()->comment('comando ejecutado');
                $table->longText('yobel_send')->nullable()->comment('datos de envio a  yobel');
                $table->longText('yobel_response')->nullable()->comment('respuesta de yobel');
                $table->unsignedTinyInteger('status')->default(0)->comment('Define si fue exitoso');
                $table->dateTime('last_check')->nullable()->comment('ultima consulta');
                $table->timestamps();
                $table->foreign('logistic_yobel_id')->references('id')->on('logistic_yobel')->onDelete('cascade');

            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('logistic_yobel_api');
            Schema::dropIfExists('logistic_yobel');

        }
    }
