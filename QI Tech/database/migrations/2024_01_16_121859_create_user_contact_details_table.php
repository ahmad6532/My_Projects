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
        Schema::create('user_contact_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('contact');
            $table->tinyInteger('type')->default(0); // 0 for phone number and 1  for email;
            $table->tinyInteger('is_contact_show')->default(0); // 0 for hide and 1 show;
            $table->timestamps();
            $table->foreign('user_id')
            ->on('users')
            ->references('id')
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
        Schema::dropIfExists('user_contact_details');
    }
};
