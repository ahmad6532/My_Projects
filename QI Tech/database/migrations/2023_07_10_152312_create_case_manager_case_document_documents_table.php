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
        Schema::create('case_manager_case_document_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('c_m_c_d_id')->index();
            $table->unsignedBigInteger('document_id')->index();
            $table->string('type');
            $table->timestamps();

            $table->foreign('c_m_c_d_id')
            ->on('case_manager_case_documents')
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
        Schema::dropIfExists('case_manager_case_document_documents');
    }
};
