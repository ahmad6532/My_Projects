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
        Schema::table('be_spoke_form_action_conditions', function (Blueprint $table) {
            $table->longText('condition_action_value')->nullable();
            $table->longText('condition_action_value_1')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('be_spoke_form_action_conditions', function (Blueprint $table) {
            $table->dropColumn(['condition_action_value','condition_action_value_1']);
        });
    }
};
