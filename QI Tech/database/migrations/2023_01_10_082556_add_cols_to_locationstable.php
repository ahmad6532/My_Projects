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
        Schema::table('locations', function (Blueprint $table) {
            $table->string('near_miss_ask_for_who')->default('1');
            $table->string('near_miss_ask_for_user_detail')->default('name');
            $table->string('near_miss_robot_in_use')->default(0);
            $table->string('near_miss_robot_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['near_miss_ask_for_who','near_miss_ask_for_user_detail','near_miss_robot_in_use','near_miss_robot_name']);
        });
    }
};
