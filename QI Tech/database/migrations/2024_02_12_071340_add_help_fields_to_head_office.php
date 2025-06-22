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
            $table->boolean('is_help_viewable')->default(false)->after('is_viewable_to_user');
            $table->string('help_description')->nullable()->after("is_help_viewable");
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
            $table->dropColumn(['is_help_viewable','help_description']);
            //
        });
    }
};
