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
            $table->boolean('is_phone_viewable')->default(false)->after('is_help_viewable');
            $table->boolean('is_email_viewable')->default(false)->after('is_phone_viewable');
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
            $table->dropColumn('is_phone_viewable');
            $table->dropColumn('is_email_viewable');
        });
    }
};
