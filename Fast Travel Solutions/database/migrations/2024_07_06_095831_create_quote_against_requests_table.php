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
        Schema::create('quote_against_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('customer_bookings')->onDelete('cascade');
            $table->foreignId('company_id')->constrained('companies')->nullable()->onDelete('cascade');
            $table->foreignId('driver_id')->constrained('drivers')->nullable()->onDelete('cascade');
            $table->foreignId('booking_req_id')->constrained('booking_request_companies')->onDelete('cascade');
            $table->string('price');
            $table->string('vehicle_type');
            $table->string('color');
            $table->string('manufacturer');
            $table->string('model');
            $table->longText('description')->nullable();
            $table->integer('status')->default(0)->comment('0=>Not Approved, 1=> Approved');
            $table->enum('operator_status',['available','un-available'])->default('available');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_against_requests');
    }
};
