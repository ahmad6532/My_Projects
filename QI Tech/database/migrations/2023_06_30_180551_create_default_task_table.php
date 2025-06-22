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
        Schema::create('default_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('be_spoke_form_id')->index();
            $table->string('title');
            $table->longText('description');
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
        Schema::dropIfExists('default_tasks');
    }
};
