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
            $table->string('technical_email')->nullable();
            $table->string('technical_phone')->nullable();
            $table->string('is_viewable_to_user')->default(0);
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
            $table->dropColumn(['technical_email','technical_phone','is_viewable_to_user']);
        });
    }
};
