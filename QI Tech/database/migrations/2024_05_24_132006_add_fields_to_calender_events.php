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
        Schema::table('calender_events', function (Blueprint $table) {
            //
            $table->time('cutoff')->default('09:00:00');
            $table->boolean('do_not_allow_submissions')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calender_events', function (Blueprint $table) {
            //
            $table->dropColumn('cutoff');
            $table->dropColumn('do_not_allow_submissions');
        });
    }
};
