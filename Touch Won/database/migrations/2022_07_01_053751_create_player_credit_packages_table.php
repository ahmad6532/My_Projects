<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerCreditPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_credit_packages', function (Blueprint $table) {
            $table->integer('credit_package_id', true);
            $table->string('package_type');
            $table->string('package_name');
            $table->integer('credit_cost');
            $table->integer('credits_value_count');
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
        Schema::dropIfExists('player_credit_packages');
    }
}
