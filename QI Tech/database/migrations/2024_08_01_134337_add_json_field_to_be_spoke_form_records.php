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
            $table->json('raw_form')->nullable();
            $table->string('record_id')->nullable();
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
            //
            $table->dropColumn('raw_form');
            $table->dropColumn('record_id');
        });
    }
};
