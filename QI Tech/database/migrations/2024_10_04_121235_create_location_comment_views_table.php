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
        Schema::create('location_comment_views', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('comment_id')->unsigned();
            $table->bigInteger('head_office_user_id')->unsigned();
            $table->bigInteger('ho_location_id')->unsigned();
            $table->timestamps();

            $table->foreign('comment_id')->references('id')->on('location_comments')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('head_office_user_id')->references('id')->on('head_office_users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('ho_location_id')->references('id')->on('head_office_locations')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('location_comment_views');
    }
};
