<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_details', function (Blueprint $table) {
            $table->integer('account_id', true)->unique('account_id');
            $table->string('vendor_id')->index('account_details_fk0');
            $table->string('player_PIN');
            $table->integer('player_id')->index('account_details_fk1');
            $table->string('is_verified');
            $table->boolean('is_deleted');
            $table->bigInteger('points');
            $table->bigInteger('credits');
            $table->timestamp('created_on')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('updated_on')->useCurrentOnUpdate()->useCurrent();
            $table->boolean('is_active');
            $table->timestamp('last_login_credit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_details');
    }
}
