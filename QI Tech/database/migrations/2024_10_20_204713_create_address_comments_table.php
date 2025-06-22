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
        Schema::create('address_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('address_id');
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned();
            $table->text('comment');
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on('address_comments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('address_id')->references('id')->on('new_contact_addresses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('address_comments');
    }
};
