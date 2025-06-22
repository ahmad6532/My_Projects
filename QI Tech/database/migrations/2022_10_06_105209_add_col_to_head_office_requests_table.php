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
        Schema::table('head_office_requests', function (Blueprint $table) {

            $table->string('address');

            $table->integer('approved_head_office_id')->unsigned()->nullable()->index();
            $table->foreign('approved_head_office_id')
                ->references('id')
                ->on('head_offices')
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
        Schema::table('head_office_requests', function (Blueprint $table) {
            $table->dropForeign(['approved_head_office_id']);
            $table->dropColumn(['approved_head_office_id','address']);
        });
    }
};
