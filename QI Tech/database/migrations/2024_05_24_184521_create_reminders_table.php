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
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('location_id')->nullable();
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->unsignedBigInteger('form_id');
            $table->foreign('form_id')->references('id')->on('be_spoke_form')->onDelete('cascade');
            $table->string('type')->nullable();
            $table->string('day')->nullable();
            $table->string('time')->nullable();
            $table->boolean('isNotified')->nullable();
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
        Schema::dropIfExists('reminders');
    }
};
