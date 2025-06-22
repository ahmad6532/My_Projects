<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_record_id')->references('id')->on('user_daily_records')->onDelete('cascade');
            $table->string('check_in_lati');
            $table->string('check_out_longi')->nullable();
            $table->string('check_in_address');
            $table->string('check_out_address')->nullable();
            $table->string('check_in_image');
            $table->string('check_out_image')->nullable();
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
        Schema::dropIfExists('attendance_details');
    }
}
