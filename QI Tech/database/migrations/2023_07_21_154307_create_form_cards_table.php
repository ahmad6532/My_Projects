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
        Schema::create('form_cards', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('default_card_id');
            
            $table->unsignedBigInteger('be_spoke_form_id');
            $table->string('name');

            $table->timestamps();

            // $table->foreign('default_card_id')
            // ->on('default_cards')
            // ->references('id')
            // ->onDelete('cascade')
            // ->onUpdate('cascade');
            
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
        Schema::dropIfExists('form_cards');
    }
};
