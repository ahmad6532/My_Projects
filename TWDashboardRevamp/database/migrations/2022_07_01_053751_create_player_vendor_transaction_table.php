<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerVendorTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_vendor_transaction', function (Blueprint $table) {
            $table->integer('trans_id', true);
            $table->integer('account_id')->index('account_id');
            $table->integer('vendor_id')->index('player_vendor_transaction_fk1');
            $table->timestamp('date')->useCurrentOnUpdate()->useCurrent();
            $table->bigInteger('creds')->nullable();
            $table->bigInteger('points')->nullable();
            $table->integer('amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_vendor_transaction');
    }
}
