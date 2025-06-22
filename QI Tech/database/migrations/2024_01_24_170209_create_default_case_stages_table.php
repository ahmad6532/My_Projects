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
        Schema::create('default_case_stages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('be_spoke_form_id');
            $table->string('name');
            $table->timestamps();

            $table->foreign('be_spoke_form_id')
            ->references('id')
            ->on('be_spoke_form')
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
        Schema::dropIfExists('default_case_stages');
    }
};
