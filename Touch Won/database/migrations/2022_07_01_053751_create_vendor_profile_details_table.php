<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorProfileDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_profile_details', function (Blueprint $table) {
            $table->integer('vendor_id', true);
            $table->string('vendor_promocode', 100)->unique('vendor_promocode');
            $table->string('user_type');
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email', 100);
            $table->string('password')->nullable();
            $table->string('phone_number', 20)->unique('phone_number');
            $table->integer('credits')->nullable();
            $table->string('address');
            $table->dateTime('created_on')->nullable();
            $table->dateTime('updated_on')->nullable();
            $table->string('status', 5);
            $table->boolean('is_verified')->nullable();
            $table->integer('is_drawer_start')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_profile_details');
    }
}
