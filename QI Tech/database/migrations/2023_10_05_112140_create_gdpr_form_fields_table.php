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
        Schema::create('gdpr_form_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gdpr_tag_id');
            $table->unsignedBigInteger('be_spoke_form_question_id');
            $table->timestamps();

            $table->unique(['gdpr_tag_id','be_spoke_form_question_id']);

            $table->foreign('gdpr_tag_id')
            ->on('gdpr_tags')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('be_spoke_form_question_id')
            ->on('be_spoke_form_questions')
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
        Schema::dropIfExists('gdpr_form_fields');
    }
};
