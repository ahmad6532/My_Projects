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
        Schema::create('case_request_information_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_request_information_id');
            $table->unsignedBigInteger('comment_document_id')->index();
            $table->timestamps();

            $table->unique(['case_request_information_id','comment_document_id'],'cricd_id');

            $table->foreign('case_request_information_id','cri_id')
            ->on('case_request_informations')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            
            $table->foreign('comment_document_id','cd_id')
            ->on('case_manager_case_comment_documents')
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
        Schema::dropIfExists('case_request_information_documents');
    }
};
