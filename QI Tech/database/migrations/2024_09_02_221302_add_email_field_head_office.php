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
            //
            $table->string('email')->nullable()->unique();
            $table->string('sites_count')->nullable();
            $table->string('staff_count')->nullable();
            $table->json('weekdays')->nullable();
            $table->json('weekends')->nullable();
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
            $table->dropColumn(['email', 'sites_count', 'staff_count', 'weekdays', 'weekends']);
        });
    }
};
