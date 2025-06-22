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
        Schema::create('approved_location_location_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('head_office_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('location_id');
            $table->timestamps();

            $table->foreign('head_office_id')
            ->on('head_offices')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('user_id')
            ->on('users')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            $table->foreign('location_id')
            ->on('locations')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            $table->unique(['head_office_id','user_id','location_id'],'houol_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approved_location_location_users');
    }
};
