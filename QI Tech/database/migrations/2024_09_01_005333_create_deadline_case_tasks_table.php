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
        Schema::create('deadline_case_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_task_id')->constrained('case_stage_tasks')->onDelete('cascade');
            $table->foreignId('default_task_id')->nullable()->constrained('default_case_stage_tasks')->onDelete('cascade');
            $table->foreignId('deadline_id')->nullable()->constrained('task_deadline_records')->onDelete('cascade');
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
        Schema::dropIfExists('deadline_case_tasks');
    }
};
