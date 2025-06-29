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
        Schema::table('case_handler_users', function (Blueprint $table) {
            //
            $table->boolean('master_stage_handler')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('case_handler_users', function (Blueprint $table) {
            //
            $table->dropColumn('master_stage_handler');
        });
    }
};
