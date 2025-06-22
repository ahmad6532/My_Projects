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
        Schema::create('new_contact_addresses', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('avatar')->nullable();
            $table->text('address');
            $table->text('address_tag')->nullable();
            $table->unsignedInteger('head_office_id');
            $table->foreign('head_office_id')->references('id')->on('head_offices')->onDelete('cascade');
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
        Schema::dropIfExists('new_contact_addresses');
    }
};
