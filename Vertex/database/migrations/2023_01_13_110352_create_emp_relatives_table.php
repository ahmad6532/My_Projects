<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelativeEmployedByViionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emp_relatives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('emp_id');
            $table->string('relative_name');
            $table->string('relative_position');
            $table->string('relative_dept');
            $table->string('relative_location')->nullable();
            $table->string('relative_relation');
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
        Schema::dropIfExists('emp_relatives');
    }
}
