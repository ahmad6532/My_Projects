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
        Schema::table('share_cases', function (Blueprint $table) {
            $table->unsignedInteger('revoke_by')->index()->nullable();

            $table->foreign('revoke_by')
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
        Schema::table('share_cases', function (Blueprint $table) {
            $table->dropForeign(['revoke_by']);
            $table->dropColumn(['revoke_by']);
        });
    }
};
