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
        Schema::create('location_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ho_location_id');
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned();
            $table->text('comment');
            $table->foreign('parent_id')->references('id')->on('location_comments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('ho_location_id')->references('id')->on('head_office_locations')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('location_comments');
    }
};
