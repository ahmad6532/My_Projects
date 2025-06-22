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
        Schema::table('national_alerts', function (Blueprint $table) {
            $table->string('schedule_later')->nullable()->default('no');
            $table->dateTime('start_time')->useCurrent();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('national_alerts', function (Blueprint $table) {
            $table->dropColumn(['schedule_later','start_time']);
        });
    }
};
