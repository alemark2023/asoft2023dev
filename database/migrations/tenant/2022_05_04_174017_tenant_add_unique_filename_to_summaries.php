<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddUniqueFilenameToSummaries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('summaries', function (Blueprint $table) {
            $table->string('unique_filename')->nullable()->after('filename')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('summaries', function (Blueprint $table) {
            $table->dropUnique(['unique_filename']);
            $table->dropColumn('unique_filename');
        });
    }
}
