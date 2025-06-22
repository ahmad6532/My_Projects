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
        Schema::create('share_case_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('share_case_id');
            $table->unsignedBigInteger('document_id');
            $table->string('type');
            $table->timestamps();

            $table->foreign('share_case_id')
            ->on('share_cases')
            ->references('id')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            
            $table->foreign('document_id')
            ->on('documents')
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
        Schema::dropIfExists('share_case_documents');
    }
};
