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
        Schema::create('case_stage_tasks', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('case_stage_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->text('title');
            $table->text('description');
            $table->string('status')->nullable();
            $table->tinyInteger('is_dead_line')->nullable();
            $table->string('dead_line_option')->nullable();
            $table->string('dead_line_user_id')->nullable();
            $table->string('dead_line_profile_id')->nullable();
            $table->string('dead_line_over_due_email')->nullable();
            $table->timestamp('dead_line_date')->nullable();
            $table->string('dead_line_unit')->nullable();
            $table->integer('dead_line_duration')->nullable();
            $table->tinyInteger('is_task_over_due')->nullable();
            $table->timestamp('task_over_due_date')->nullable();     
            $table->string('task_over_due_option')->nullable();
            $table->string('task_over_due_user_id')->nullable();
            $table->string('task_over_due_profile_id')->nullable();
            $table->string('task_over_due_email')->nullable();   
            $table->string('task_over_due_unit')->nullable();    
            $table->integer('task_over_due_duration')->nullable(); 
            $table->tinyInteger('is_default_task')->default(0);
            
            $table->timestamps();

            $table->foreign('case_stage_id')
                ->references('id')
                ->on('case_stages')
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
        Schema::dropIfExists('case_stage_tasks');
    }
};
