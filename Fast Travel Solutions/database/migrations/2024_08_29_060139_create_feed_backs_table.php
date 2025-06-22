<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feed_backs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('customer_bookings')->onDelete('cascade');
            $table->integer('on_time');
            $table->integer('driver_attitude');
            $table->integer('vehicle_standard');
            $table->integer('service_level');
            $table->longText('comments')->nullable();
            $table->string('email');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));    

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_backs');
    }
};
