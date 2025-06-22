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
        Schema::table('be_spoke_form_questions', function (Blueprint $table) {
            $table->unsignedBigInteger('form_card_id')->index()->nullable();
            $table->unsignedBigInteger('default_field_id')->index()->nullable();

            $table->foreign('form_card_id')
            ->on('form_cards')
            ->references('id')
            ->onDelete('set null')
            ->onUpdate('cascade');

            $table->foreign('default_field_id')
            ->on('default_fields')
            ->references('id')
            ->onDelete('set null')
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
        Schema::table('be_spoke_form_questions', function (Blueprint $table) {
            $table->dropForeign('be_spoke_form_questions_default_field_id_foreign');
            $table->dropForeign('be_spoke_form_questions_form_card_id_foreign');
            
            $table->dropColumn(['form_card_id','default_field_id']);
        });
    }
};
