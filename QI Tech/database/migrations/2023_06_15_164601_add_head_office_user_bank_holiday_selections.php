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
        Schema::create('h_o_user_bank_holiday_selections', function (Blueprint $table) {

            $table->id();
            $table->bigInteger('head_office_user_id')->unsigned();
            $table->integer('reference_id');
            $table->string('name');
            $table->date('date');
            $table->tinyInteger('is_working')->default(0); //0 means no 1 means yes working
            $table->timestamps();

            $table->foreign('head_office_user_id')
                  ->references('id')
                  ->on('head_office_users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('h_o_user_bank_holiday_selections');
    }
};
