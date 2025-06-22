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
        Schema::table('system_links', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('case_id')->nullable()->after('id');
            $table->foreign('case_id')->on('head_office_cases')->references('id');
            $table->unsignedBigInteger('comment_id')->nullable()->after('case_id');
            $table->foreign('comment_id')->on('case_manager_case_comments')->references('id');
            $table->integer('clicks')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_links', function (Blueprint $table) {
            //
            $table->dropForeign(['case_id']);
            $table->dropColumn('case_id');
            $table->dropForeign(['comment_id']);
            $table->dropColumn('comment_id');
            $table->dropColumn('clicks');
        });
    }
};
