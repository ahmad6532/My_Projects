<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->index('user_id');
            $table->string('user_type');
            $table->integer('credits')->nullable();
            $table->integer('credit_package_id')->nullable();
            $table->decimal('amount', 10, 0)->nullable();
            $table->string('txn_id')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('currency_code')->nullable();
            $table->string('payment_gross')->nullable();
            $table->string('promocode_status')->nullable();
            $table->timestamp('created_on')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('updated_on')->useCurrent();
            $table->string('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
