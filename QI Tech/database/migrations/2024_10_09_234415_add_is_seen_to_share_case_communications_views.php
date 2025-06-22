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
        Schema::table('share_case_communications_views', function (Blueprint $table) {
            //
            $table->boolean('is_seen')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('share_case_communications_views', function (Blueprint $table) {
            //
            $table->dropColumn('is_seen');
        });
    }
};
