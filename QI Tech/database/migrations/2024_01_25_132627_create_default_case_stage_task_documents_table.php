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
        Schema::create('default_case_stage_task_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('default_case_stage_task_id');
            $table->unsignedBigInteger('document_id');
            $table->string('type');
            $table->timestamps();

            $table->foreign('default_case_stage_task_id','d_c')
            ->on('default_case_stage_tasks')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('document_id')
            ->on('documents')
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
        Schema::dropIfExists('default_case_stage_task_documents');
    }
};
