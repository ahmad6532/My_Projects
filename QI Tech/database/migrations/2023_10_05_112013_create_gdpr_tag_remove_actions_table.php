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
        Schema::create('gdpr_tag_remove_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gdpr_tag_id')->unique();
            $table->integer('remove_after_number');
            $table->string('remove_after_unit');
            $table->timestamps();

            $table->foreign('gdpr_tag_id')
            ->on('gdpr_tags')
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
        Schema::dropIfExists('gdpr_tag_remove_actions');
    }
};
