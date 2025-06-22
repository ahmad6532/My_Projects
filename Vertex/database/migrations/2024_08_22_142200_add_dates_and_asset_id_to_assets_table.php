<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatesAndAssetIdToAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('asset_id')->nullable()->after('serial_no');
            $table->date('disposed_date')->nullable()->after('purchase_date');
            $table->date('assigned_date')->nullable()->after('disposed_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('disposed_date');
            $table->dropColumn('assigned_date');
            $table->dropColumn('asset_id');
        });
    }
}
