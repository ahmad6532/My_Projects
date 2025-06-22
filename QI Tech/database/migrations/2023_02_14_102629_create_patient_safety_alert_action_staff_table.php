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
        Schema::create('psa_action_staff', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('action_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->bigInteger('received_alert_id')->unsigned();
            $table->timestamps();
            
            $table->unique(['action_id', 'user_id','received_alert_id']);
            
            $table->foreign('received_alert_id')
                ->references('id')
                ->on('location_received_alerts');

            $table->foreign('action_id')
                ->references('id')
                ->on('psa_actions');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('psa_action_staff');
    }
};
