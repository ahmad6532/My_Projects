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
        Schema::table('organisation_settings', function (Blueprint $table) {
            $table->string('location_section_heading_color', 15)->default('#5ac1b6');
            $table->string('location_form_setting_color', 15)->default('#4dd6f0');
            $table->string('location_button_color', 15)->default('#5ac1b8');
            $table->string('location_button_text_color', 15)->default('#fff');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organisation_settings', function (Blueprint $table) {
            $table->dropColumn(['location_section_heading_color', 'location_form_setting_color', 'location_button_color', 'location_button_text_color']);
        });
    }
};
