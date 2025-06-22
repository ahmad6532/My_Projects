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
        Schema::create('default_case_stage_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('default_case_stage_id')->index();
            $table->string('title');
            $table->longText('description');
            
            $table->tinyInteger('type')->default(0);
            $table->string('type_ids')->nullable();
            
            $table->tinyInteger('is_dead_line')->nullable();
            $table->string('dead_line_unit')->nullable();
            $table->integer('dead_line_duration')->nullable();
            $table->string('dead_line_option')->nullable();
            $table->string('dead_line_user_id')->nullable();
            $table->string('dead_line_profile_id')->nullable();
            $table->string('dead_line_over_due_email')->nullable();
            $table->string('dead_line_start_from')->nullable();
            
            
            $table->tinyInteger('is_task_over_due')->nullable();
            $table->string('task_over_due_unit')->nullable();    
            $table->integer('task_over_due_duration')->nullable();    
            $table->string('task_over_due_option')->nullable();
            $table->string('task_over_due_user_id')->nullable();
            $table->string('task_over_due_profile_id')->nullable();
            $table->string('task_over_due_email')->nullable();
            $table->timestamp('dead_line_date')->nullable();
            
            $table->timestamp('task_over_due_date')->nullable();  
              
            $table->timestamps();

            $table->foreign('default_case_stage_id')
            ->on('default_case_stages')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default_case_stage_tasks');
    }
};
