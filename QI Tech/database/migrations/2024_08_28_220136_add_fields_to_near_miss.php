<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('near_miss_managers', function (Blueprint $table) {
            //
            $table->boolean('is_quick_report')->default(false);
            $table->boolean('is_qr_code')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('near_miss_managers', function (Blueprint $table) {
            //
            $table->dropColumn('is_quick_report');
            $table->dropColumn('is_qr_code');
        });
    }
};
