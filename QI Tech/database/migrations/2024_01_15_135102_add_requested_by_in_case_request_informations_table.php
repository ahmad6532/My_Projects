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
        Schema::table('case_request_informations', function (Blueprint $table) {
            $table->unsignedInteger('requested_by');

            $table->foreign('requested_by')
            ->on('users')
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
        Schema::table('case_request_informations', function (Blueprint $table) {
            $table->dropForeign(['requested_by']);
            $table->dropColumn(['requested_by']);
        });
    }
};
