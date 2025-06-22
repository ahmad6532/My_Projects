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
        Schema::create('contact_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_id');
            $table->unsignedBigInteger('address_id');
            $table->tinyInteger('is_present_address')->default(0);
            $table->timestamps();

            $table->unique(['contact_id', 'address_id']);

            $table->foreign('contact_id')->on('contacts')->references('id')->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('address_id')->on('addresses')->references('id')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_addresses');
    }
};
