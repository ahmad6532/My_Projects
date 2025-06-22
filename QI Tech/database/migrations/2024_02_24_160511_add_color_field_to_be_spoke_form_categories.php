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
        Schema::table('be_spoke_form_categories', function (Blueprint $table) {
            $table->string('color', 15)->default('#000')->nullable();
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
        Schema::table('be_spoke_form_categories', function (Blueprint $table) {
            $table->dropColumn('color');
            //
        });
    }
};
