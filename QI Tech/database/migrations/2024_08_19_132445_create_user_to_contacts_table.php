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
        Schema::create('user_to_contacts', function (Blueprint $table) {
            $table->unsignedBigInteger('contact_id');
            $table->foreign('contact_id')->references('id')->on('new_contacts')->onDelete('cascade');
            $table->unsignedBigInteger('head_office_user_id');
            $table->foreign('head_office_user_id')->references('id')->on('head_office_users')->onDelete('cascade');
            $table->id();
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
        Schema::dropIfExists('user_to_contacts');
    }
};
