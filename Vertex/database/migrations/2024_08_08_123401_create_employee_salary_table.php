<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeSalaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_salary', function (Blueprint $table) {
            $table->id();
            $table->integer('emp_id');
            $table->integer('pay_period_id');
            $table->date('joining_date');
            $table->integer('salary_type_id');
            $table->decimal('working_hours', 10, 2)->nullable();
            $table->decimal('salary_per_hour', 10, 2)->nullable();
            $table->decimal('working_days', 10, 2)->nullable();
            $table->decimal('total_salary', 10, 2)->nullable();
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
        Schema::dropIfExists('employee_salary');
    }
}
