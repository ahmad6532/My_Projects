<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('head_offices', function (Blueprint $table) {
            $table->string('primary_color', 15)->nullable();
            $table->string('icon_color', 15)->nullable();
            $table->string('highlight_color', 15)->nullable();
            $table->string('login_highlight_color', 15)->nullable();
            $table->string('company_logo')->nullable();
            $table->string('portal_logo')->nullable();
            $table->string('background_image')->nullable();
            $table->string('title_text')->nullable();
            $table->string('portal_text')->nullable();
            $table->string('sign_button_color', 15)->nullable();
            $table->string('link_token')->nullable();
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
            //
            $table->dropColumn('primary_color');
            $table->dropColumn('icon_color');
            $table->dropColumn('highlight_color');
            $table->dropColumn('login_highlight_color');
            $table->dropColumn('company_logo');
            $table->dropColumn('portal_logo');
            $table->dropColumn('background_image');
            $table->dropColumn('title_text');
            $table->dropColumn('portal_text');
            $table->dropColumn('sign_button_color');
            $table->dropColumn('link_token');
        });
    }
};
