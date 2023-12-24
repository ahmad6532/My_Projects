<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorCurrencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_currency', function (Blueprint $table) {
            $table->integer('vendor_id', true);
            $table->integer('credits');
            $table->boolean('current_credit_package_status');
            $table->integer('current_active_credit_package_id')->index('vendor_currency_fk1');
            $table->timestamp('bought_on')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('expires_on')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_currency');
    }
}
