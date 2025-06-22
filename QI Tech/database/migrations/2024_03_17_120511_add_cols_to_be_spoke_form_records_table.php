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
        Schema::table('head_office_cases', function (Blueprint $table) {
            $table->boolean('submitable_to_nhs_lfpse')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('head_office_cases', function (Blueprint $table) {
            $table->dropColumn('submitable_to_nhs_lfpse');
        });
    }
};
