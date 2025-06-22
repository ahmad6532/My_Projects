<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('website_brandings', function (Blueprint $table) {
            $table->id();
            $table->string('favicon')->nullable(); // Store path to favicon
            $table->string('logo')->nullable(); // Store path to logo
            $table->string('google_map_api_key')->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('whatsapp_link')->nullable();
            $table->string('twitter_link')->nullable();
            $table->string('linkedin_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('email_link')->nullable();
            $table->string('trip_pilot_link')->nullable();
            $table->string('trust_advisor_link')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_brandings');
    }
};
