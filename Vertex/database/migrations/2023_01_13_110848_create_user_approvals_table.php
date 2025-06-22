<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('emp_id');
            $table->string('job_position');
            $table->date('joining_date');
            $table->BigInteger('phone_issued');
            $table->BigInteger('starting_sal');
            $table->enum('approved_by_HR',['0','1'])->default('0');
            $table->enum('approved_by_PM',['0','1'])->default('0');
            $table->enum('approved_by_CEO',['0','1'])->default('0');
            $table->enum('is_active',['1','0'])->default('1');
            $table->enum('is_deleted',['0','1'])->default('0');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('emp_id')->references('id')->on('employee_details')->onDelete('cascade');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_approvals');
    }
}
