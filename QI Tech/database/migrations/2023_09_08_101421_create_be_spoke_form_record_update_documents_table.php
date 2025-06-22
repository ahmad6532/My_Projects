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
        Schema::create('be_spoke_form_record_update_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('be_spoke_form_record_update_id');
            $table->unsignedBigInteger('document_id');
            $table->string('type');
            $table->timestamps();

            $table->foreign(['be_spoke_form_record_update_id'],'bsfrud_bsfru')
            ->on('be_spoke_form_record_updates')
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
        Schema::dropIfExists('be_spoke_form_record_update_documents');
    }
};
