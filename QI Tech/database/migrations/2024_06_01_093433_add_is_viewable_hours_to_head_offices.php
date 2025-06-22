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
        Schema::table('head_offices', function (Blueprint $table) {
            $table->boolean('is_viewable_hours')->default(false);
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
        Schema::table('head_offices', function (Blueprint $table) {
            //
            $table->dropColumn('is_viewable_hours');
        });
    }
};
