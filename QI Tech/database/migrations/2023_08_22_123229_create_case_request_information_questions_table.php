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
        Schema::create('case_request_information_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_request_information_id');
            $table->text('question');
            $table->text('answer')->nullable();

            
            $table->timestamps();


            $table->foreign(['case_request_information_id'],'case_q_i_foreign_id')
            ->on('case_request_informations')
            ->references('id')
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
        Schema::dropIfExists('case_request_information_questions');
    }
};
