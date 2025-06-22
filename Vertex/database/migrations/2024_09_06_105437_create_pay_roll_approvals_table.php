<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayRollApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_roll_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pay_period_id');
            $table->foreign('pay_period_id')->references('id')->on('pay_period')->cascadeOnDelete();
            $table->integer('emp_id');
            $table->string('emp_type');
            $table->string('department')->nullable();
            $table->string('designation');
            $table->integer('basic_salary');
            $table->integer('late');
            $table->integer('leave');
            $table->integer('absent');
            $table->integer('absent_deduction');
            $table->integer('sales_incentive')->nullable();
            $table->integer('allowances')->nullable();
            $table->integer('loan')->nullable();
            $table->integer('deduction')->nullable();
            $table->integer('monthly_incom')->nullable();
            $table->integer('monthly_tax')->nullable();
            $table->integer('net_salary')->nullable();
            $table->integer('status');
            $table->date('paid_date')->nullable();
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
        Schema::dropIfExists('pay_roll_approvals');
    }
}
