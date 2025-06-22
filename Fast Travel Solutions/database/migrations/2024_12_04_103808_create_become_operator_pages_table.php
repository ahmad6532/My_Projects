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
        Schema::create('become_operator_pages', function (Blueprint $table) {
            $table->id();
            $table->string('operator_about_img');
            $table->string('operator_about_heading');
            $table->longText('operator_about_desc');
            $table->string('operator_registration_heading');
            $table->string('operator_discount_title');
            $table->string('operator_register_img');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('become_operator_pages');
    }
};
