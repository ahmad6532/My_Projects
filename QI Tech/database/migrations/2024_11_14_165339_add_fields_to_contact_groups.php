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
        Schema::table('contact_groups', function (Blueprint $table) {
            $table->string('color')->default('#000');
            $table->string('icon')->default('1');
            $table->string('icon_color')->default('#fff');
            $table->string('text_color')->default('#fff');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact_groups', function (Blueprint $table) {
            $table->dropColumn(['color', 'icon', 'icon_color', 'text_color']);
        });
    }
};
