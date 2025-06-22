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
        Schema::create('head_office_user_timings', function (Blueprint $table) {
            $table->id();
            $table->time('monday_start_time')->nullable();
            $table->time('monday_end_time')->nullable();
            $table->time('tuesday_start_time')->nullable();
            $table->time('tuesday_end_time')->nullable();
            $table->time('wednesday_start_time')->nullable();
            $table->time('wednesday_end_time')->nullable();
            $table->time('thursday_start_time')->nullable();
            $table->time('thursday_end_time')->nullable();
            $table->time('friday_start_time')->nullable();
            $table->time('friday_end_time')->nullable();

            $table->time('saturday_start_time')->nullable();
            $table->time('saturday_end_time')->nullable();
            
            $table->time('sunday_start_time')->nullable();
            $table->time('sunday_end_time')->nullable();

            $table->tinyInteger('is_open_monday')->default(0);
            $table->tinyInteger('is_open_tuesday')->default(0);
            $table->tinyInteger('is_open_wednesday')->default(0);
            $table->tinyInteger('is_open_thursday')->default(0);
            $table->tinyInteger('is_open_friday')->default(0);
            $table->tinyInteger('is_open_saturday')->default(0);
            $table->tinyInteger('is_open_sunday')->default(0);

            $table->timestamps();

            $table->foreign('id')
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
        Schema::dropIfExists('head_office_user_timings');
    }
};
