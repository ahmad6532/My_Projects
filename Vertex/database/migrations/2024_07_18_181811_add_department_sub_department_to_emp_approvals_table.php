<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepartmentSubDepartmentToEmpApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emp_approvals', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('sub_department_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emp_approvals', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('sub_department_id')->nullable();
        });
    }
}
