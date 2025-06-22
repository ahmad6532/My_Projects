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
        Schema::create('temp_task_forms', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('head_office_user_id')->unsigned();
            $table->foreign('head_office_user_id')
                  ->references('id')
                  ->on('head_office_users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->unsignedInteger('head_office_id')->nullable();
            $table->foreign('head_office_id')->references('id')->on('head_offices');
            $table->unsignedBigInteger('task_id')->unique();
            $table->foreign('task_id')->references('id')->on('default_case_stage_tasks')->onDelete('cascade');
            $table->json('form_json');
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
        Schema::dropIfExists('temp_task_forms');
    }
};
