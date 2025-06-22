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
        Schema::create('case_manager_case_comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('case_id')->unsigned();
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned();
            $table->text('comment');
            $table->timestamps();
            
            $table->foreign('parent_id')
                ->references('id')
                ->on('case_manager_case_comments')
                ->onUpdate('cascade')
                ->onDelete('cascade');

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
        Schema::dropIfExists('case_manager_case_comments');
    }
};
