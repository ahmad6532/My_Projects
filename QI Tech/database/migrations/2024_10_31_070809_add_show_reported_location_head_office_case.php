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
        Schema::table('head_office_cases', function (Blueprint $table) {
            //
            $table->boolean('show_reported_location')->default(false);
            $table->unsignedInteger('saved_location')->nullable();
            $table->foreign('saved_location')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('head_office_cases', function (Blueprint $table) {
            $table->dropForeign(['saved_location']);
            $table->dropColumn(['saved_location', 'show_reported_location']);
        });
    }
};
