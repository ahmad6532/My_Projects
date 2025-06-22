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
        Schema::table('near_misses', function (Blueprint $table) {
            $table->string('delete_reason')->nullable();
            $table->bigInteger('deleted_by')->unsigned()->nullable();
            $table->dateTime('deleted_timestamp')->nullable();
            $table->string('dispensed_at_hub')->nullable();

        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('near_misses', function (Blueprint $table) {
            $table->dropColumn(['delete_reason','deleted_by','deleted_timestamp','dispensed_at_hub']);
        });
    }
};
