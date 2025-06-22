<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeCompensationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_compensation_details', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('amount');
            $table->enum('type_of', ['allowance', 'contribution', 'deduction']);
            $table->boolean('is_active')->default(false);
            $table->boolean('is_deleted')->default(false);
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
        Schema::dropIfExists('employee_compensation_details');
    }
}
