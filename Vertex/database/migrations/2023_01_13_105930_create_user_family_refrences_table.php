<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFamilyRefrencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_family_refrences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('emp_id');
            $table->string('memeber_name');
            $table->string('memeber_relation');
            $table->integer('memeber_age');
            $table->string('memeber_occupation')->nullable();
            $table->string('place_of_work')->nullable();
            $table->enum('is_active',['1','0'])->default('1');
            $table->enum('is_deleted',['0','1'])->default('0');
            $table->foreign('emp_id')->references('id')->on('employee_details')->onDelete('cascade');
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
        Schema::dropIfExists('user_family_refrences');
    }
}
