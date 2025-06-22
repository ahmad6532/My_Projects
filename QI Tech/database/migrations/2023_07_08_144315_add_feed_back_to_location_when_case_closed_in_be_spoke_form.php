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
        Schema::table('be_spoke_form', function (Blueprint $table) {
            $table->tinyInteger('feed_back_location_when_case_closed_in_be_spoke_form')->default(0);
            $table->tinyInteger('requires_final_approval')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('be_spoke_form', function (Blueprint $table) {
            $table->dropColumn(['feed_back_location_when_case_closed_in_be_spoke_form','requires_final_approval']);
        });
    }
};
