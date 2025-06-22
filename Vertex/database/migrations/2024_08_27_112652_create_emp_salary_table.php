<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpSalaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emp_salary', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_details_id');
            $table->decimal('basic_salary', 8, 0);
            $table->decimal('taxable_salary', 8, 0);
            $table->decimal('net_salary', 8, 0);
            $table->enum('pay_period', ['Fornightly', 'Monthly', 'Weekly', 'Daily']);
            $table->enum('salary_type', ['Fixed Based', 'Hourly Based']);
            $table->date('first_working_date')->nullable();
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
        Schema::dropIfExists('emp_salary');
    }
}
