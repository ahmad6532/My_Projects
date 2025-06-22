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
            $table->string('portal_text_color', 15)->default('#fff');
            $table->string('portal_section_heading_color', 15)->default('#72c4ba');
            $table->string('portal_primary_btn_color', 15)->default('#72c4ba');
            $table->string('portal_primary_btn_text_color', 15)->default('#fff');
            $table->string('sign_btn_text_color', 15)->default('#fff');
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
            $table->dropColumn(['portal_text_color', 'portal_section_heading_color', 'portal_primary_btn_color', 'portal_primary_btn_text_color', 'sign_btn_text_color']);
        });
    }
};
