<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerProfileDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_profile_details', function (Blueprint $table) {
            $table->integer('player_id', true);
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('email', 100)->nullable()->unique('email');
            $table->string('phone_number', 20)->nullable()->unique('phone_number');
            $table->string('street_name')->nullable();
            $table->string('state', 50)->nullable();
            $table->string('country', 20)->nullable();
            $table->string('zip_code')->nullable();
            $table->timestamp('created_on')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('updated_on')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_profile_details');
    }
}
