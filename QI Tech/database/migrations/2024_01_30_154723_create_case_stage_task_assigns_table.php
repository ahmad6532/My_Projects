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
        Schema::create('case_stage_task_assigns', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('task_id')->unsigned();
            $table->bigInteger('head_office_user_id')->unsigned();
            $table->timestamps();

            $table->unique(['task_id', 'head_office_user_id'], 'cmcta_th'); // same task should not be assignable to same person twice !

            $table->foreign('task_id')
                ->references('id')
                ->on('case_stage_tasks')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            
            $table->foreign('head_office_user_id')
                ->references('id')
                ->on('head_office_users')
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
        Schema::dropIfExists('case_stage_task_assigns');
    }
};
