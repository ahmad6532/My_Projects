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
        Schema::create('location_users', function (Blueprint $table) {
            $table->id();

            $table->integer('user_id')->unsigned()->index();
            $table->integer('location_id')->unsigned()->index();

            $table->integer('type')->nullable();
            $table->timestamps();
            $table->unique(['user_id','location_id']);



            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');


            $table->foreign('location_id')
                ->references('id')
                ->on('locations')
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
        Schema::dropIfExists('location_users');
    }
};
