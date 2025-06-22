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
        Schema::create('business_pages', function (Blueprint $table) {
            $table->id();
            $table->string('hero_section_heading');
            $table->string('hero_section_desc');
            $table->string('hero_section_img');
            $table->string('industry_section_heading');
            $table->string('industry_section_img');
            $table->string('testimonial_heading');
            $table->string('testimonial_desc');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_pages');
    }
};
