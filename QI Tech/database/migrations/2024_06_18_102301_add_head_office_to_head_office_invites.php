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
        Schema::table('head_office_invites', function (Blueprint $table) {
            //
            $table->unsignedInteger('head_office_id');
            $table->foreign('head_office_id')
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
        Schema::table('head_office_invites', function (Blueprint $table) {
            //
            $table->dropForeign('head_office_id');
            $table->dropColumn('head_office_id');
        });
    }
};
