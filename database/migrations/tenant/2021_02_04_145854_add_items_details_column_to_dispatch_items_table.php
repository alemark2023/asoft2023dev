<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemsDetailsColumnToDispatchItemsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dispatch_items', function (Blueprint $table) {
			$table->decimal('unit_value', 16, 6);
			$table->string('affectation_igv_type_id')->nullable();
			$table->decimal('total_base_igv', 12, 2);
			$table->decimal('percentage_igv', 12, 2);
			$table->decimal('total_igv', 12, 2);
			$table->string('system_isc_type_id')->nullable();
			$table->decimal('total_base_isc', 12, 2)->default(0);
			$table->decimal('percentage_isc', 12, 2)->default(0);
			$table->decimal('total_isc', 12, 2)->default(0);
			$table->decimal('total_base_other_taxes', 12, 2)->default(0);
			$table->decimal('percentage_other_taxes', 12, 2)->default(0);
			$table->decimal('total_other_taxes', 12, 2)->default(0);
			$table->decimal('total_taxes', 12, 2);

			$table->string('price_type_id')->nullable();
			$table->decimal('unit_price', 16, 6);

			$table->decimal('total_value', 12, 2);
			$table->decimal('total_charge', 12, 2)->default(0);
			$table->decimal('total_discount', 12, 2)->default(0);
			$table->decimal('total', 12, 2);

			$table->json('attributes')->nullable();
			$table->json('discounts')->nullable();
			$table->json('charges')->nullable();
			$table->unsignedInteger('warehouse_id')->nullable();

			$table->foreign('warehouse_id')->references('id')->on('warehouses');
			$table->foreign('affectation_igv_type_id')->references('id')->on('cat_affectation_igv_types');
			$table->foreign('system_isc_type_id')->references('id')->on('cat_system_isc_types');
			$table->foreign('price_type_id')->references('id')->on('cat_price_types');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('dispatch_items', function (Blueprint $table) {
			$table->dropForeign('dispatch_items_affectation_igv_type_id_foreign');
			$table->dropForeign('dispatch_items_system_isc_type_id_foreign');
			$table->dropForeign('dispatch_items_price_type_id_foreign');
			$table->dropForeign('dispatch_items_warehouse_id_foreign');

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
			$table->dropColumn('warehouse_id');
		});
	}
}
