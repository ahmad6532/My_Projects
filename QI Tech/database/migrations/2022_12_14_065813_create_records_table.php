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
        Schema::create('be_spoke_form_records', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('form_id')->unsigned();
            $table->bigInteger('location_id')->unsigned();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->integer('priority');
            $table->timestamps();

            //should be a linked form foreign relation otherwise, its hard to tell which type of record is this. for example dispencing incident
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('be_spoke_form_records');
    }
};
