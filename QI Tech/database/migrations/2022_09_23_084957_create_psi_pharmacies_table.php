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
        Schema::create('psi_pharmacies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('psi_registration_number')->unsigned();
            $table->string('trading_name')->nullable();
            $table->string('street_1')->nullable();
            $table->string('street_2')->nullable();
            $table->string('street_3')->nullable();
            $table->string('town')->nullable();
            $table->string('county')->nullable();
            $table->string('rpb_owner')->nullable();

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
        Schema::dropIfExists('psi_pharmacies');
    }
};
