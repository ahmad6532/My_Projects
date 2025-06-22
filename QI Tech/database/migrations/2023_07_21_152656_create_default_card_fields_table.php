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
        Schema::create('default_fields', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('default_card_id');
            $table->string('field_name');
            $table->string('db_field_name');
            $table->timestamps();

            // $table->foreign('default_card_id')
            // ->on('default_cards')
            // ->references('id')
            // ->onDelete('cascade')
            // ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default_fields');
    }
};
