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
        Schema::table('head_office_access_rights', function (Blueprint $table) {
            //
            $table->boolean('is_access_locations')->default(false);
            $table->json('locations')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('head_office_access_rights', function (Blueprint $table) {
            //
            $table->dropColumn('is_access_locations');
            $table->dropColumn('locations');
        });
    }
};
