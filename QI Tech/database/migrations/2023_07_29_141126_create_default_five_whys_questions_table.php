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
        Schema::create('default_five_whys_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('head_office_id')->index();
            $table->text('question');
            $table->timestamps();

            $table->foreign('head_office_id')
            ->on('head_offices')
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
        Schema::dropIfExists('default_five_whys_questions');
    }
};
