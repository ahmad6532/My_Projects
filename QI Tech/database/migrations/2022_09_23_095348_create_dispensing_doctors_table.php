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
        Schema::create('dispensing_doctors', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sicbl_name')->unsigned();
            $table->string('practice_name')->nullable();
            $table->string('practice_code')->nullable();
            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->string('address_3')->nullable();
            $table->string('address_4')->nullable();
            $table->string('postcode')->nullable();
            $table->string('total_gp_s')->nullable();
            $table->string('dispensing_gp_s')->nullable();

            $table->bigInteger('database_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dispensing_doctors');
    }
};
