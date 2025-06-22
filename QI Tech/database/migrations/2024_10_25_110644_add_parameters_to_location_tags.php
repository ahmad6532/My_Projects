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
        DB::table('location_tags')->truncate();
        Schema::table('location_tags', function (Blueprint $table) {
            //
            $table->unsignedInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations');
            $table->integer('icon')->default(0);
            $table->string('icon_color')->default('#ffffff');
            $table->string('text_color')->default('#ffffff');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('location_tags', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
            $table->dropColumn(['location_id', 'icon', 'icon_color', 'text_color']);
        });
    }
};
