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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('selected_head_office_id')->unsigned()->index()->nullable();
            $table->foreign('selected_head_office_id')
                ->references('id')
                ->on('head_offices')
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['selected_head_office_id']);
            $table->dropColumn('selected_head_office_id');
        });
    }
};
