<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLocationRegulatoryBodiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_regulatory_bodies', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('country')->nullable();
            $table->string('regulatory')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('location_regulatory_bodies');
    }
}
