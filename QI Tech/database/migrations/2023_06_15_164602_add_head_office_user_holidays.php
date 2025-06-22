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
        Schema::create('head_office_user_holidays', function (Blueprint $table) {

            $table->id();
            $table->bigInteger('head_office_user_id')->unsigned();
            $table->date('away_from');
            $table->date('return_on');
            $table->integer('total_days');
            $table->string('type');
            $table->bigInteger('linked_api_holiday_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('head_office_user_id')
                  ->references('id')
                  ->on('head_office_users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('linked_api_holiday_id')
                  ->references('id')
                  ->on('h_o_user_bank_holiday_selections')
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
        Schema::dropIfExists('head_office_user_holidays');
    }
};
