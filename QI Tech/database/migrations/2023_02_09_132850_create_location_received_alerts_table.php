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
        Schema::create('location_received_alerts', function (Blueprint $table) {
            $table->id();
            $table->integer('location_id')->unsigned();
            $table->bigInteger('national_alert_id')->unsigned();
            $table->string('status')->default('unactioned');
            $table->string('alert_year');
            $table->dateTime('alert_date_time');
            $table->text('received_object_copy');
            $table->timestamps();
            
            $table->unique(['national_alert_id', 'location_id']);
            $table->foreign('national_alert_id')
                ->references('id')
                ->on('national_alerts');
            
            $table->foreign('location_id')
                ->references('id')
                ->on('locations');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('location_received_alerts');
    }
};
