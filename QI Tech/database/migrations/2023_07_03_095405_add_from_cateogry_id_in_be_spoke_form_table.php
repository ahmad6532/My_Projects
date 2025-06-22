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
        Schema::table('be_spoke_form', function (Blueprint $table) {
            $table->unsignedBigInteger('be_spoke_form_category_id');

            $table->foreign('be_spoke_form_category_id')
            ->references('id')
            ->on('be_spoke_form_categories')
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
        Schema::table('be_spoke_form', function (Blueprint $table) {
            $table->dropForeign(['be_spoke_form_category_id']);
            $table->dropColumn(['be_spoke_form_category_id']);
        });
    }
};
