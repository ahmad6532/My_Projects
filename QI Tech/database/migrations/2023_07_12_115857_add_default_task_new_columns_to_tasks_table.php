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
        Schema::table('case_manager_case_tasks', function (Blueprint $table) {
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('case_manager_case_tasks', function (Blueprint $table) {
            $table->dropColumn(['is_dead_line','dead_line_option','dead_line_user_id',
            'dead_line_profile_id','dead_line_over_due_email','is_task_over_due','task_over_due_option','task_over_due_user_id',
            'task_over_due_profile_id','task_over_due_email','dead_line_date','task_over_due_date','dead_line_unit','dead_line_duration','task_over_due_unit','task_over_due_duration']);
        });
    }
};
