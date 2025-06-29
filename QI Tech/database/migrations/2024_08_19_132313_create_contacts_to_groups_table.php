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
        Schema::create('contact_to_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_id');
            $table->foreign('contact_id')->references('id')->on('new_contacts')->onDelete('cascade');
            $table->unsignedBigInteger('group_id');
            $table->foreign('group_id')->references('id')->on('contact_groups')->onDelete('cascade');
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
        Schema::dropIfExists('contact_to_groups');
    }
};
