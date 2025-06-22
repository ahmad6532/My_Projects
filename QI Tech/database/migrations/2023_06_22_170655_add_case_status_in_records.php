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
        Schema::table('be_spoke_form_records', function (Blueprint $table) {
            $table->tinyInteger('case_status')->default(0); //0 means on going 1 means completed
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
        Schema::table('be_spoke_form_records', function (Blueprint $table) {
            $table->dropColumn('case_status');
            
        });
    }
};
