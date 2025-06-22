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
        Schema::create('northern_ireland_list', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pharmacy_registration_number')->unsigned();
            $table->string('owner_name')->nullable();
            $table->string('trading_name')->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('town')->nullable();
            $table->string('postcode')->nullable();

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
        Schema::dropIfExists('northern_ireland_list');
    }
};
