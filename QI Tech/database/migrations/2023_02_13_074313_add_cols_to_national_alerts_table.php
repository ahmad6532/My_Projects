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
        Schema::table('national_alerts', function (Blueprint $table) {
            $table->text('suggested_actions')->nullable();
            $table->boolean('send_to_all_countries')->default(0);
            $table->boolean('send_to_all_designations')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('national_alerts', function (Blueprint $table) {
            $table->dropColumn(['suggested_actions','send_to_all_countries','send_to_all_designations']);
        });
    }
};
