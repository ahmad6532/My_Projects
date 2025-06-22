<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeEmployeeDetailsColumnsToBigint extends Migration
{
    public function up()
    {
        Schema::table('employee_details', function (Blueprint $table) {

            $table->bigInteger('emp_phone')->change();
            $table->bigInteger('cnic')->change();
        });
    }

    public function down()
    {
        Schema::table('employee_details', function (Blueprint $table) {
            $table->integer('emp_phone')->change();
            $table->integer('cnic')->change();
        });
    }
}
