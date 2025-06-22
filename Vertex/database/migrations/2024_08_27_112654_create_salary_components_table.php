<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_components', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_details_id');
            $table->unsignedBigInteger('component_type_id'); // Reference to salary_component_types
            $table->decimal('amount', 10, 0);
            $table->boolean('tax_applicable')->default(false);
            $table->boolean('status')->default(false);
            $table->decimal('percentage', 5, 0)->nullable();
            $table->timestamps();
            // Foreign Key Constraints
            $table->foreign('employee_details_id')->references('id')->on('employee_details')->onDelete('cascade');
            $table->foreign('component_type_id')->references('id')->on('salary_component_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salary_components');
    }
}
