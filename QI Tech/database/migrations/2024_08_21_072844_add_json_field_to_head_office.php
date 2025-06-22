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
        Schema::table('head_office_users', function (Blueprint $table) {
            //
            $table->json('user_can_view')->nullable();
            $table->json('certain_locations')->nullable();
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
        Schema::table('head_office_users', function (Blueprint $table) {
            //
            $table->dropColumn('user_can_view');
            $table->dropColumn('certain_locations');
            $table->dropColumn('assigned_locations');
        });
    }
};
