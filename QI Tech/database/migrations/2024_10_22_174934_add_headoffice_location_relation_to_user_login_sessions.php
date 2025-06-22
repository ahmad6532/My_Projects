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
        Schema::table('user_login_sessions', function (Blueprint $table) {
            $table->unsignedInteger('head_office_id')->nullable();
            $table->foreign('head_office_id')->references('id')->on('head_offices');
            $table->unsignedInteger('location_id')->nullable();
            $table->foreign('location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_login_sessions', function (Blueprint $table) {
            $table->dropForeign(['head_office_id']);
            $table->dropForeign(['location_id']);        
            $table->dropColumn(['head_office_id', 'location_id']);
        });
    }
};
