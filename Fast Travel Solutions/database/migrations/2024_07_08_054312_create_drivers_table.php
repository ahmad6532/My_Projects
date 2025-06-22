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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('driver_email')->unique();
            $table->string('phone');
            $table->string('profile_picture')->nullable();
            $table->string('address');
            $table->string('national_insurance_num')->nullable();
            $table->string('driver_pco_license_num');
            $table->foreignId('fleet_type_id')->constrained('fleet_types')->onDelete('cascade');
            // $table->string('vehicle_type');
            // $table->string('vehicle_make');
            // $table->string('vehicle_model');
            // $table->foreignId('fleet_manufacturer_id')->constrained('fleet_manufacturers')->onDelete('cascade');
            // $table->foreignId('fleet_model_id')->constrained('fleet_models')->onDelete('cascade');
            $table->string('fleet_manufacturer_id');
            $table->string('fleet_model_id');
            $table->string('vehicle_reg_num');
            $table->string('vehicle_color');
            $table->string('mot');
            $table->string('vehicle_insurance')->nullable();
            $table->string('vehicle_insurance_expiry')->nullable();
            $table->string('driving_license_front_pic');
            $table->string('driving_license_back_pic');
            $table->string('driving_pco_license_pic');
            $table->string('vehicle_pco_license_pic');
            $table->string('vehicle_insurance_pic')->nullable();
            $table->string('logbook_pic');
            $table->string('vehicle_mot_pic');
            $table->enum('active_status', ['pending', 'accepted', 'banned'])->default('pending');

            // $table->integer('active_status')->default(0); 
            // $table->string('other_document_pic');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));    

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
