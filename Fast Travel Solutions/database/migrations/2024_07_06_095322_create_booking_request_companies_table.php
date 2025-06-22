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
        Schema::create('booking_request_companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('customer_bookings')->onDelete('cascade');
            $table->string('quote_price')->default(0);
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->onDelete('cascade');            
            $table->string('status')->default(0);
            $table->enum('available_status',['available','un-available'])->default('available');
            $table->enum('booking_quote_status',['un-quoted','quoted','job-offer','change_request'])->default('un-quoted');
            $table->string('admin_quote_price')->default(0);
            $table->longText('description')->default(NULL);
            $table->string('token')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_request_companies');
    }
};
