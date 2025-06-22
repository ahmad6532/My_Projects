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
        Schema::create('shared_case_approved_emails', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            
            $table->text('description')->nullable();
            $table->unsignedBigInteger('be_spoke_form_id');
            $table->timestamps();

            $table->foreign('be_spoke_form_id')
            ->on('be_spoke_form')
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
        Schema::dropIfExists('shared_case_approved_emails');
    }
};
