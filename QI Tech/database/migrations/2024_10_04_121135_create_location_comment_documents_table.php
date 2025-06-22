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
        Schema::create('location_comment_documents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('comment_id')->unsigned();
            $table->bigInteger('document_id')->unsigned();
            $table->string('type')->nullable();
            $table->timestamps();
            $table->foreign('comment_id')->references('id')->on('location_comments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('document_id')->references('id')->on('documents')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('location_comment_documents');
    }
};
