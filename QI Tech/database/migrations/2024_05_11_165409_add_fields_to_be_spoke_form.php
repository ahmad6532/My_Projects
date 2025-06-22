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
            //
            $table->integer('limit_by_per_user_value')->default(1);
            $table->integer('limit_by_per_location_value')->default(1);
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
            //
            $table->dropColumn('limit_by_per_user_value');
            $table->dropColumn('limit_by_per_location_value');
        });
    }
};
