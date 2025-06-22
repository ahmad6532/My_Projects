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
        Schema::create('task_deadline_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('default_case_stage_tasks_id')->nullable();
            $table->foreign('default_case_stage_tasks_id')->references('id')->on('default_case_stage_tasks')->onDelete('cascade');
            $table->enum('task_type', ['deadline', 'overdue'])->default('deadline'); // Indicates whether it’s a deadline or overdue task
            $table->integer('duration')->nullable(); // Duration in the chosen unit
            $table->enum('unit', ['days', 'weeks', 'months', 'years'])->nullable(); // Unit for duration
            $table->enum('start_from', [
                'incident_date', 
                'reported_date', 
                'task_started', 
                'task_complete', 
                'stage_started', 
                'stage_complete'
            ])->nullable(); // Start point for the duration
            $table->unsignedBigInteger('incident_date_selected')->nullable();
            $table->unsignedBigInteger('task_started_selected')->nullable();
            $table->unsignedBigInteger('task_completed_selected')->nullable();
            $table->unsignedBigInteger('stage_started_selected')->nullable();
            $table->unsignedBigInteger('stage_completed_selected')->nullable();
            $table->enum('action_option', [
                'do_nothing', 
                'move_task_to_another_user_random', 
                'move_user', 
                'move_profile', 
                'mail_user', 
                'mail_profile', 
                'mail_custom'
            ])->nullable(); // Action to take when the task is due or overdue
            $table->json('user_ids')->nullable(); // Store multiple user IDs as JSON
            $table->json('profile_ids')->nullable(); // Store multiple profile IDs as JSON
            $table->json('emails')->nullable(); // Store multiple email addresses as JSON
            $table->text('email_template')->nullable(); // Template for emails
            $table->boolean('is_task_overdue')->default(false); // Flag to indicate if the task is overdue
            $table->string('email_profile_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_deadline_records');
    }
};
