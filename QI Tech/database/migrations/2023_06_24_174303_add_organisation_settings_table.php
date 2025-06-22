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
        Schema::create('organisation_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('head_office_id')->index();
            $table->string('name'); 
            $table->string('bg_color_code'); 
            $table->string('font');
            $table->timestamps();
            
            $table->foreign('head_office_id')
            ->references('id')
            ->on('head_offices')
            ->onDelete('cascade')
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
        Schema::dropIfExists('organisation_settings');
    }
};
