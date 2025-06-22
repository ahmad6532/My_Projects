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
        Schema::table('dispensing_doctors', function (Blueprint $table) {
            $table->dropColumn(['sicbl_name']);
        });
        Schema::table('dispensing_doctors', function (Blueprint $table) {
            $table->string('sicbl_name',200);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dispensing_doctors', function (Blueprint $table) {
            $table->dropColumn(['sicbl_name']);
        });
        Schema::table('dispensing_doctors', function (Blueprint $table) {
            $table->bigInteger('sicbl_name')->unsigned();
        });
    }
};
