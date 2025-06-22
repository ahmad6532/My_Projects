<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryComponentTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_component_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // E.g., Rent, Health Insurance, Pension, etc.
            $table->enum('type', ['Allowance', 'Contribution', 'Deduction']); // 'allowance', 'contribution', 'deduction'
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
        Schema::dropIfExists('salary_component_types');
    }
}
