<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('company_id');
            $table->integer('branch_id');
            $table->string('device_name');
            $table->tinyInteger('device_type_id');
            $table->ipAddress('device_ip');
            $table->string('serial_number');
            $table->string('device_model');
            $table->integer('enrolled_users')->nullable();
            $table->date('expiry_date');
            $table->tinyInteger('heartbeat');
            $table->enum('status',['OFFLINE','ONLINE'])->nullable();
            $table->enum('is_deleted',['1','0'])->default('0');
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
        Schema::dropIfExists('devices');
    }
}
