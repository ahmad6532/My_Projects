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
        Schema::table('head_office_user_incident_settings', function (Blueprint $table) {
            //
            $table->json('assigned_locations')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('head_office_user_incident_settings', function (Blueprint $table) {
            //
            $table->dropColumn('assigned_locations');
        });
    }
};
