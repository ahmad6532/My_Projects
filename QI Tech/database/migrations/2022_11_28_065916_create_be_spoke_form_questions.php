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
        Schema::create('be_spoke_form_questions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('form_id')->unsigned();
            $table->bigInteger('stage_id')->unsigned();
            $table->bigInteger('group_id')->unsigned()->nullable();
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->string('question_type');
            $table->string('question_name');
            $table->string('question_title');
            $table->string('question_required');
            $table->string('question_min')->nullable();
            $table->string('question_max')->nullable();
            $table->text('question_values')->nullable();
            $table->string('question_select_multiple')->nullable();
            $table->string('question_select_loggedin_user')->nullable();
            //$table->string('question_select_loggedin_user')->nullable();
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
        Schema::dropIfExists('be_spoke_form_questions');
    }
};
