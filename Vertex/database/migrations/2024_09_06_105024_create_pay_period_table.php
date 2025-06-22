<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayPeriodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_period', function (Blueprint $table) {
            $table->id();
            $table->string('payroll_type');
            $table->string('company_id');
            $table->string('branch_id');
            $table->integer('total_emp');
            $table->string('department_id')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('remarks')->nullable();
            $table->string('net_salary')->nullable();
            $table->enum('closed',['0','1'])->default('0');
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
        Schema::dropIfExists('pay_period');
    }
}
