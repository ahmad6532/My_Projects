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
        Schema::create('location_opening_hours', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('location_id')->unsigned();

            $table->tinyInteger('open_monday')->nullable()->default(0);
            $table->string('monday_start_time')->nullable();
            $table->string('monday_end_time')->nullable();

            $table->tinyInteger('open_tuesday')->nullable()->default(0);
            $table->string('tuesday_start_time')->nullable();
            $table->string('tuesday_end_time')->nullable();

            $table->tinyInteger('open_wednesday')->nullable()->default(0);
            $table->string('wednesday_start_time')->nullable();
            $table->string('wednesday_end_time')->nullable();

            $table->tinyInteger('open_thursday')->nullable()->default(0);
            $table->string('thursday_start_time')->nullable();
            $table->string('thursday_end_time')->nullable();

            $table->tinyInteger('open_friday')->nullable()->default(0);
            $table->string('friday_start_time')->nullable();
            $table->string('friday_end_time')->nullable();

            $table->tinyInteger('open_saturday')->nullable()->default(0);
            $table->string('saturday_start_time')->nullable();
            $table->string('saturday_end_time')->nullable();

            $table->tinyInteger('open_sunday')->nullable()->default(0);
            $table->string('sunday_start_time')->nullable();
            $table->string('sunday_end_time')->nullable();
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
        Schema::dropIfExists('location_opening_hours');
    }
};
