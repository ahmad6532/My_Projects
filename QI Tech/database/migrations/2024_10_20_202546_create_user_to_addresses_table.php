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
        Schema::create('user_to_addresses', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('address_id');
                $table->foreign('address_id')->references('id')->on('new_contact_addresses')->onDelete('cascade');
                $table->unsignedBigInteger('head_office_user_id');
                $table->foreign('head_office_user_id')->references('id')->on('head_office_users')->onDelete('cascade');
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
        Schema::dropIfExists('user_to_addresses');
    }
};
