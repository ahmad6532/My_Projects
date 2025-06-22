<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('become_driver_pages', function (Blueprint $table) {
            $table->id();
            $table->string('driver_heading');
            $table->longText('driver_desc');
            $table->string('driver_discount_title');
            $table->string('driver_img');
            $table->longText('driver_details');
            $table->string('driver_details_img');
            $table->string('title_1');
            $table->longText('desc_1');
            $table->string('title_2');
            $table->longText('desc_2');
            $table->string('title_3');
            $table->longText('desc_3');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('become_driver_pages');
    }
};
