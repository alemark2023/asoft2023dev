<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToHotelRentsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('hotel_rents', function (Blueprint $table) {
			$table->date('input_date')->nullable()->after('payment_number_operation');
			$table->string('input_time', 8)->nullable()->after('input_date');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('hotel_rents', function (Blueprint $table) {
			$table->dropColumn('input_date');
			$table->dropColumn('input_time');
		});
	}
}
