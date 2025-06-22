<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeCompensationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_compensation', function (Blueprint $table) {
            $table->id();
            $table->integer('emp_id');
            $table->integer('type_id');
            $table->integer('amount');
            $table->enum('type_of', ['allowance', 'contribution', 'deduction']);
            $table->boolean('is_active')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_taxable');
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
        Schema::dropIfExists('employee_compensation');
    }
}
