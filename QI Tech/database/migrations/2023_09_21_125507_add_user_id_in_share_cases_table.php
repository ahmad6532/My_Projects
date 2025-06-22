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
        Schema::table('share_case_request_extensions', function (Blueprint $table) {
            $table->unsignedInteger('requested_by')->nullable();
            $table->unsignedInteger('user_id')->nullable();

            $table->text('head_office_notes')->nullable();
            $table->foreign('requested_by')
            ->on('users')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('user_id')
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
        Schema::table('share_case_request_extensions', function (Blueprint $table) {
            $table->dropForeign(['requested_by']);
            $table->dropForeign(['user_id']);

            $table->dropColumn(['requested_by','user_id','head_office_notes']);
            //$table->dropColumn(['requested_by','user_id']);
        });
    }
};
