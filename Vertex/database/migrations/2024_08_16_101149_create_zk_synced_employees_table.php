<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZkSyncedEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zk_synced_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emp_id')->references('id')->on('employee_details')->onDelete('cascade');
            $table->enum('synced',['0','1']);
            $table->enum('action',['create','delete']);
            $table->string('old_branch')->nullable();
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
        Schema::dropIfExists('zk_synced_employees');
    }
}
