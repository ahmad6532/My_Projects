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
        Schema::table('locations', function (Blueprint $table) {
            
            $table->dropForeign(['location_regulatory_body_id']);
            $table->dropIndex(['location_regulatory_body_id']);


            $table->integer('location_regulatory_body_id')->nullable()->unsigned()->index()->change();
            $table->foreign('location_regulatory_body_id')
                  ->references('id')
                  ->on('location_regulatory_bodies')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            //
        });
    }
};
