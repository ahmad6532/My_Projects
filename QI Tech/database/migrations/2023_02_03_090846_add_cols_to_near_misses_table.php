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
        Schema::table('near_misses', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('error')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('near_misses', function (Blueprint $table) {
            $table->dropColumn(['user_id','error']);
        });
    }
};
