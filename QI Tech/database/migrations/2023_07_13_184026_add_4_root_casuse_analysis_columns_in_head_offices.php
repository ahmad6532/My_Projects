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
            $table->tinyInteger('is_fish_bone')->default(0);
            $table->tinyInteger('is_fish_bone_compulsory')->default(0);
            $table->tinyInteger('is_five_whys')->default(0);
            $table->tinyInteger('is_five_whys_compulsory')->default(0);
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
            $table->dropColumn(['is_fish_bone','is_fish_bone_compulsory','is_five_whys','is_five_whys_compulsory']);
        });
    }
};
