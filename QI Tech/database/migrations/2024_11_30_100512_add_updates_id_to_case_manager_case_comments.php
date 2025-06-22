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
        Schema::table('case_manager_case_comments', function (Blueprint $table) {
            $table->unsignedBigInteger('record_update_id')->nullable();
            $table->foreign('record_update_id')->references('id')->on('case_manager_case_comments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('case_manager_case_comments', function (Blueprint $table) {
            $table->dropForeign(['record_update_id']);
            $table->dropColumn('record_update_id');
        });
    }
};
