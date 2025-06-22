<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('branch_id');
            $table->BigInteger('emp_id')->unique();
            $table->string('emp_name');
            $table->enum('emp_gender',['M','F']);
            $table->string('emp_email')->unique();
            $table->string('emp_image')->nullable();
            $table->date('Dob');
            $table->BigInteger('emp_phone');
            $table->BigInteger('cnic')->unique();
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('emp_address');
            $table->date('join_date');
            $table->string('added_by');
            $table->string('nationality');
            $table->string('city_of_birth');
            $table->string('Religion');
            $table->string('blood_group');
            $table->string('marital_status');
            $table->string('spouse')->nullable();
            $table->enum('is_licensed',['0','1'])->default('0');
            $table->enum('is_independant',['0','1'])->default('0');
            $table->enum('transport',['0','1'])->default('0');
            $table->enum('home_owned',['1','0'])->default('1');
            $table->string('transport_type');
            $table->BigInteger('registration_no')->nullable();
            $table->enum('driving_license',['0','1'])->default('0');
            $table->BigInteger('license_no')->nullable();
            $table->enum('is_active',['1','0'])->default('1');
            $table->enum('is_deleted',['0','1'])->default('0');
            
        
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');

            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_details');
    }
}
