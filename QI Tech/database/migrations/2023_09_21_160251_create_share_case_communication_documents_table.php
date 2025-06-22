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
        Schema::create('share_case_communication_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('share_case_communication_id');
            $table->unsignedBigInteger('document_id');
            $table->string('type');
            $table->timestamps();

            $table->foreign(['share_case_communication_id'],'s_c_c_s_c_c_d')
            ->on('share_case_communications')
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
        Schema::dropIfExists('share_case_communication_documents');
    }
};
