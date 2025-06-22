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
            $table->boolean('is_allow_two_way')->default(false);
            //
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
            //
            $table->dropColumn('is_allow_two_way');
        });
    }
};
