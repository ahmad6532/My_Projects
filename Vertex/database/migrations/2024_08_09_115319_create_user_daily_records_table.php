<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDailyRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('user_daily_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emp_id')->references('id')->on('employee_details')->onDelete('cascade');
            $table->string('device_serial_no')->nullable();
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->time('pull_time')->nullable();
            $table->date('dated');
            $table->enum('present',['0','1']);
            $table->enum('leave',['full leave','half leave','short leave'])->nullable();
            $table->string('leave_type')->nullable();
            $table->string('working_hours');
            $table->integer('check_in_type')->nullable();
            $table->integer('check_out_type')->nullable();
            $table->ipAddress('check_in_ip')->nullable();
            $table->ipAddress('check_out_ip')->nullable();
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
        Schema::dropIfExists('user_daily_records');
    }
}
