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
        Schema::create('be_spoke_form_record_data', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('record_id')->unsigned();
            $table->bigInteger('question_id')->unsigned();
            $table->longText('question_value')->nullable();
            $table->timestamps();

            //in future testing//
            //$table->foreign('record_id')->references('id')->on('be_spoke_form_records')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('be_spoke_form_record_data');
    }
};
