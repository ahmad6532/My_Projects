<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInThemeSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('theme_setting', function (Blueprint $table) {
            $table->string('navbar_heading_color')->nullable()->after('text_color');
            $table->string('navbar_background_color')->nullable()->after('navbar_heading_color');
            $table->string('primary_color')->nullable()->after('navbar_background_color');
            $table->string('sidebar_text_color')->nullable()->after('primary_color');
            $table->string('sub_heading_text_color')->nullable()->after('heading_color');
            $table->string('paragraph_text_color')->nullable()->after('icon_color');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('theme_setting', function (Blueprint $table) {
            $table->dropColumn([
                'navbar_heading_color',
                'navbar_background_color',
                'primary_color',
                'sidebar_text_color',
                'sub_heading_text_color',
                'paragraph_text_color'
            ]);
        });
    }
}
