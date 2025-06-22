<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_manager_case_tasks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('case_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->text('title');
            $table->text('description');
            $table->string('status')->nullable();
            $table->timestamps();

            $table->foreign('case_id')
                ->references('id')
                ->on('head_office_cases')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('case_manager_case_tasks');
    }
};
