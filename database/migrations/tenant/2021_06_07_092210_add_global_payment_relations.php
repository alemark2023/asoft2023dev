<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    /**
     * Class AddGlobalPaymentRelations
     */
    class AddGlobalPaymentRelations extends Migration {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up() {
            //
            Schema::create('global_payment_relations', function (Blueprint $table) {
                //
                $table->increments('id');
                $table->unsignedInteger('global_payments_id')->nullable()->comment('relacion con global_payments');

                $table->unsignedInteger('user_id')->nullable()->index()->comment('id de usuario');
                $table->text('payment_type')->nullable()->comment('Nombre de la clase del modelo relacionado.');
                $table->text('state_type_id')->nullable()->comment('Estado relacionado ');
                $table->text('payment_method_type_id')->nullable()->comment('Metodo de pago relacionado');
                $table->text('currency_type_id')->nullable()->comment('Moneda');
                $table->decimal('exchange_rate', 12, 2)->nullable()->comment('tasa de cambio');
                $table->decimal('total', 12, 2)->nullable()->comment('total');
                $table->date('date_of_payment')->nullable()
                      ->comment('Fecha de pago del documento. afecta a los no payments_id');

                /** Relacion con ingreso */
                $table->unsignedInteger('bank_id')->nullable()->index()->comment('Id de banco');
                $table->unsignedInteger('cash_id')->nullable()->index()->comment('Id de caja');
                /*
                id	soap_type_id	destination_id      destination_type                payment_id
                1	01	            1                   App\Models\Tenant\BankAccount	1
                payment_type	                    user_id	created_at	        updated_at
                App\Models\Tenant\DocumentPayment	1	    2021-03-19 23:00:51	2021-03-19 23:00:51
                */
                $table->unsignedInteger('document_id')->nullable()->index()->comment('Id de documento');
                $table->unsignedInteger('document_payment_id')->nullable()->index()->comment('Id de pago de documento');

                $table->unsignedInteger('expenses_id')->nullable()->index()->comment('Id de gastos');
                $table->unsignedInteger('expense_payments_id')->nullable()->index()->comment('Id de pago de gastos');

                $table->unsignedInteger('sale_notes_id')->nullable()->index()->comment('Id de nota de venta');
                $table->unsignedInteger('sale_note_payments_id')->nullable()->index()
                      ->comment('Id de pago de nota de venta');

                $table->unsignedInteger('quotations_id')->nullable()->index()->comment('Id de cotizaciones');
                $table->unsignedInteger('quotation_payments_id')->nullable()->index()
                      ->comment('Id de pago de cotizacion');

                $table->unsignedInteger('purchases_id')->nullable()->index()->comment('Id de Compras');
                $table->unsignedInteger('purchase_payments_id')->nullable()->index()->comment('Id de pago de compras');

                $table->unsignedInteger('contracts_id')->nullable()->index()->comment('Id de contratos');
                $table->unsignedInteger('contract_payments_id')->nullable()->index()
                      ->comment('Id de pago de contratos');

                $table->unsignedInteger('technical_services_id')->nullable()->index()
                      ->comment('Id de servicio tecnico');
                $table->unsignedInteger('technical_service_payments_id')->nullable()->index()
                      ->comment('Id de pago de servicio tecnico');

                $table->unsignedInteger('income_id')->nullable()->index()->comment('Id de ingresos');
                $table->unsignedInteger('income_payments_id')->nullable()->index()->comment('Id de pago de ingresos');

                $table->unsignedInteger('cash_transactions_id')->nullable()->index()
                      ->comment('Id de transacciones de efectivo');

                $table->unsignedInteger('associated_record_payment_id')->nullable()->index()
                      ->comment('Id de pagos relacionados');

                $table->tinyInteger('changed')->default(0)->comment('Si ha cambiado');
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down() {
            //
            Schema::dropIfExists('global_payment_relations');

        }
    }
