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
        Schema::create('customer_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->onDelete('cascade');
            $table->string('booking_from_lat');
            $table->string('booking_from_long');
            $table->string('booking_to_lat');
            $table->string('booking_to_long');
            $table->string('booking_from_loc_name');
            $table->string('booking_to_loc_name');
            $table->string('booking_date');
            $table->string('booking_local_date');
            $table->string('booking_time');
            $table->string('booking_local_time');
            $table->longText('booking_desc')->nullable();
            $table->string('return_date')->nullable();
            $table->string('return_local_date')->nullable();
            $table->string('return_time')->nullable();
            $table->string('return_local_time')->nullable();
            $table->string('head_passenger_mobile')->nullable();
            $table->string('head_passenger_email');
            $table->string('head_passenger_name')->nullable();
            $table->string('total_passenger')->nullable();
            $table->string('promo_code')->nullable();
            $table->foreignId('car_type_id')->nullable()->constrained('fleet_types')->onDelete('cascade');
            $table->string('confirm_via_email')->nullable();
            $table->string('confirm_via_sms')->nullable();
            $table->float('total_distance');
            $table->string('booking_price');
            $table->string('deduction_price');
            $table->string('tracking_number');
            $table->string('meet_n_greet')->nullable();
            $table->enum('booking_status',['pending','accepted','rejected','change_request'])->default('pending');
            $table->integer('booking_return_status')->default(0)->comment('0=> Not Return 1=> Return');
            $table->integer('active_status')->default(1)->comment('0=> Not active 1= Active');
            $table->enum('admin_status',['auto','manual','admin_booking'])->default('auto');
            $table->integer('is_deleted')->default(0)->comment('0=> Not deleted 1= Deleted');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_bookings');
    }
};
