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
        Schema::table('default_case_stages', function (Blueprint $table) {
            $table->integer('label')->nullable();
        });
        Schema::table('default_case_stage_tasks', function (Blueprint $table) {
            $table->integer('label')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('default_case_stages', function (Blueprint $table) {
            $table->dropColumn(['label']);
        });
        Schema::table('default_case_stage_tasks', function (Blueprint $table) {
            $table->dropColumn(['label']);
        });
    }
};
