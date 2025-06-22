<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_contact_links', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('link');
            $table->text('description')->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('contact_id');
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('contact_id')->on('new_contacts')->references('id')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_contact_links');
    }
};
