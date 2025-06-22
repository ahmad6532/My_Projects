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
        Schema::create('lfpse_errors', function (Blueprint $table) {
            $table->id();
            $table->string('status')->nullable();
            $table->string("severity")->nullable();
            $table->string("message")->nullable();
            $table->unsignedBigInteger('record_id');
            $table->foreign('record_id')->references('id')->on('be_spoke_form_records')->onDelete('cascade');
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
        Schema::dropIfExists('lfpse_errors');
    }
};
