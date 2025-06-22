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
        Schema::create('location_otp_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations');
            $table->string('otp');
            $table->string('ip')->nullable();
            $table->string('os')->nullable();
            $table->string('device')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('location_otp_logs');
    }
};
