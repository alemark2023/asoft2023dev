<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class ChangeItemRelSuscriptionplans extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('item_rel_suscription_plans', function (Blueprint $table) {
                //
                $table->json('item')->nullable();
                $table->decimal('quantity', 12)->default(0)->nullable();
                $table->decimal('unit_value', 16, 2)->default(0)->nullable();
                $table->char('affectation_igv_type_id', 255)->nullable();
                $table->decimal('total_base_igv', 12)->default(0)->nullable();
                $table->decimal('percentage_igv', 12)->default(0)->nullable();
                $table->decimal('total_igv', 12)->default(0)->nullable();
                $table->char('system_isc_type_id', 255)->nullable();
                $table->decimal('total_base_isc', 12, 2)->default(0)->nullable();
                $table->decimal('percentage_isc', 12, 2)->default(0)->nullable();
                $table->decimal('total_isc', 12, 2)->default(0)->nullable();
                $table->decimal('total_base_other_taxes', 12, 2)->default(0)->nullable();
                $table->decimal('percentage_other_taxes', 12, 2)->default(0)->nullable();
                $table->decimal('total_other_taxes', 12, 2)->default(0)->nullable();
                $table->decimal('total_taxes', 12, 2)->default(0)->nullable();
                $table->char('price_type_id', 255)->nullable();
                $table->decimal('unit_price', 16, 6)->default(0)->nullable();
                $table->decimal('total_value', 12, 2)->default(0)->nullable();
                $table->decimal('total_charge', 12, 2)->default(0)->nullable();
                $table->decimal('total_discount', 12, 2)->default(0)->nullable();
                $table->decimal('total', 12, 2)->default(0)->nullable();
                $table->json('attributes')->nullable();
                $table->json('discounts')->nullable();
                $table->json('charges')->nullable();
                $table->text('additional_information')->nullable();
                $table->unsignedInteger('warehouse_id')->default(0)->nullable();
                $table->longText('name_product_pdf')->nullable();
            });
            Schema::table('suscription_plans', function (Blueprint $table) {
                $table->string('currency_type_id')->nullable()->after('name');
                $table->string('payment_method_type_id')->nullable()->after('currency_type_id');

                $table->float('exchange_rate_sale', 13, 3)->default(0)->nullable()->after('payment_method_type_id');
                $table->float('total_prepayment', 12, 2)->default(0)->nullable()->after('exchange_rate_sale');
                $table->float('total_charge', 12, 2)->default(0)->nullable()->after('total_prepayment');
                $table->float('total_discount', 12, 2)->default(0)->nullable()->after('total_charge');
                $table->float('total_exportation', 12, 2)->default(0)->nullable()->after('total_discount');
                $table->float('total_free', 12, 2)->default(0)->nullable()->after('total_exportation');
                $table->float('total_taxed', 12, 2)->default(0)->nullable()->after('total_free');
                $table->float('total_unaffected', 12, 2)->default(0)->nullable()->after('total_taxed');
                $table->float('total_exonerated', 12, 2)->default(0)->nullable()->after('total_unaffected');
                $table->float('total_igv', 12, 2)->default(0)->nullable()->after('total_exonerated');
                $table->float('total_igv_free', 12, 2)->default(0)->nullable()->after('total_igv');
                $table->float('total_base_isc', 12, 2)->default(0)->nullable()->after('total_igv_free');
                $table->float('total_isc', 12, 2)->default(0)->nullable()->after('total_base_isc');
                $table->float('total_base_other_taxes', 12, 2)->default(0)->nullable()->after('total_isc');
                $table->float('total_other_taxes', 12, 2)->default(0)->nullable()->after('total_base_other_taxes');
                $table->float('total_taxes', 12, 2)->default(0)->nullable()->after('total_other_taxes');
                $table->float('total_value', 12, 2)->default(0)->nullable()->after('total_taxes');
                $table->json('charges')->nullable()->after('total_value');
                $table->json('attributes')->nullable()->after('charges');
                $table->json('discounts')->nullable()->after('attributes');
                $table->json('prepayments')->nullable()->after('discounts');
                $table->json('related')->nullable()->after('prepayments');
                $table->json('perception')->nullable()->after('related');
                $table->json('detraction')->nullable()->after('perception');
                $table->json('legends')->nullable()->after('detraction');
                $table->longText('terms_condition')->nullable()->after('legends');


            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::table('item_rel_suscription_plans', function (Blueprint $table) {
                //

                $table->dropColumn('item');
                $table->dropColumn('quantity');
                $table->dropColumn('unit_value');
                $table->dropColumn('affectation_igv_type_id');
                $table->dropColumn('total_base_igv');
                $table->dropColumn('percentage_igv');
                $table->dropColumn('total_igv');
                $table->dropColumn('system_isc_type_id');
                $table->dropColumn('total_base_isc');
                $table->dropColumn('percentage_isc');
                $table->dropColumn('total_isc');
                $table->dropColumn('total_base_other_taxes');
                $table->dropColumn('percentage_other_taxes');
                $table->dropColumn('total_other_taxes');
                $table->dropColumn('total_taxes');
                $table->dropColumn('price_type_id');
                $table->dropColumn('unit_price');
                $table->dropColumn('total_value');
                $table->dropColumn('total_charge');
                $table->dropColumn('total_discount');
                $table->dropColumn('total');
                $table->dropColumn('attributes');
                $table->dropColumn('discounts');
                $table->dropColumn('charges');
                $table->dropColumn('additional_information');
                $table->dropColumn('warehouse_id');
                $table->dropColumn('name_product_pdf');
            });


            Schema::table('suscription_plans', function (Blueprint $table) {
                $table->dropColumn('currency_type_id');
                $table->dropColumn('payment_method_type_id');
                $table->dropColumn('exchange_rate_sale');
                $table->dropColumn('total_prepayment');
                $table->dropColumn('total_charge');
                $table->dropColumn('total_discount');
                $table->dropColumn('total_exportation');
                $table->dropColumn('total_free');
                $table->dropColumn('total_taxed');
                $table->dropColumn('total_unaffected');
                $table->dropColumn('total_exonerated');
                $table->dropColumn('total_igv');
                $table->dropColumn('total_igv_free');
                $table->dropColumn('total_base_isc');
                $table->dropColumn('total_isc');
                $table->dropColumn('total_base_other_taxes');
                $table->dropColumn('total_other_taxes');
                $table->dropColumn('total_taxes');
                $table->dropColumn('total_value');
                $table->dropColumn('charges');
                $table->dropColumn('attributes');
                $table->dropColumn('discounts');
                $table->dropColumn('prepayments');
                $table->dropColumn('related');
                $table->dropColumn('perception');
                $table->dropColumn('detraction');
                $table->dropColumn('legends');
                $table->dropColumn('terms_condition');


            });
        }
    }
