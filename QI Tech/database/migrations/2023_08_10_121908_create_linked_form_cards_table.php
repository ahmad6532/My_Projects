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
        Schema::create('connected_form_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_card_id')->unique();
            $table->unsignedBigInteger('group_id');
            $table->timestamps();

            $table->foreign('form_card_id')
            ->on('form_cards')
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
        Schema::dropIfExists('connected_form_cards');
    }
};
