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
        Schema::create('gphc_locations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('gphc_registration_number');
            $table->string('owner_name')->nullable();
            $table->string('trading_name')->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('address_line_3')->nullable();
            $table->string('town')->nullable();
            $table->string('county')->nullable();
            $table->string('postcode')->nullable();
            $table->string('country')->nullable();
            $table->string('registered_internet_pharmacy')->nullable();
            $table->string('primary_care_trust')->nullable();
            $table->string('has_notices_or_conditions')->nullable();
            $table->string('expiry_date')->nullable();

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
        Schema::dropIfExists('gphc_locations');
    }
};
