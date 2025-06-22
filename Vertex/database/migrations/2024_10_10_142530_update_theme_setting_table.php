<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateThemeSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('theme_setting', function (Blueprint $table) {
            $table->string('theme_name')->nullable()->change();
            $table->string('heading_color')->nullable()->change();
            $table->string('text_color')->nullable()->change();
            $table->string('sidebar_color')->nullable()->change();
            $table->string('sidebar_background_color')->nullable()->change();
            $table->string('body_background_color')->nullable()->change();
            $table->string('header_background_color')->nullable()->change();
            $table->string('sidebar_hover')->nullable()->change();
            $table->string('button_background_color')->nullable()->change();
            $table->string('button_text_color')->nullable()->change();
            $table->string('btn_border_color')->nullable()->change();
            $table->string('pagination_active_bg')->nullable()->change();
            $table->string('pagination_active_color')->nullable()->change();
            $table->string('tabs_color')->nullable()->change();
            $table->string('tabs_active_color')->nullable()->change();
            $table->string('tabs_active_background_color')->nullable()->change();
            $table->string('icon_color')->nullable()->change();
            $table->string('is_active')->default('0')->change();
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
            $table->string('theme_name')->nullable(false)->change();
            $table->string('heading_color')->nullable(false)->change();
            $table->string('text_color')->nullable(false)->change();
            $table->string('sidebar_color')->nullable(false)->change();
            $table->string('sidebar_background_color')->nullable(false)->change();
            $table->string('body_background_color')->nullable(false)->change();
            $table->string('header_background_color')->nullable(false)->change();
            $table->string('sidebar_hover')->nullable(false)->change();
            $table->string('button_background_color')->nullable(false)->change();
            $table->string('button_text_color')->nullable(false)->change();
            $table->string('btn_border_color')->nullable(false)->change();
            $table->string('pagination_active_bg')->nullable(false)->change();
            $table->string('pagination_active_color')->nullable(false)->change();
            $table->string('tabs_color')->nullable(false)->change();
            $table->string('tabs_active_color')->nullable(false)->change();
            $table->string('tabs_active_background_color')->nullable(false)->change();
            $table->string('icon_color')->nullable(false)->change();
            $table->string('is_active')->default(null)->change(); // Reverting the default change
        });
    }
}
