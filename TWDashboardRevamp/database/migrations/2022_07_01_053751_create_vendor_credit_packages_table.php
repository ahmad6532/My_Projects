<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorCreditPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_credit_packages', function (Blueprint $table) {
            $table->integer('credit_package_id', true);
            $table->string('package_name')->nullable();
            $table->integer('package_expiry_date');
            $table->integer('amount');
            $table->integer('credits_value_count');
            $table->dateTime('created_on');
            $table->dateTime('updated_on');
            $table->boolean('is_enabled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_credit_packages');
    }
}
