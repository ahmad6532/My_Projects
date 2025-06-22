<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMarkStatusToUserDailyRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_daily_records', function (Blueprint $table) {
            $table->enum('mark_in_status', ['0', '1'])->nullable()->default(null)->after('check_out_ip');
            $table->enum('mark_out_status', ['0', '1'])->nullable()->default(null)->after('mark_in_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_daily_records', function (Blueprint $table) {
            //
        });
    }
}
