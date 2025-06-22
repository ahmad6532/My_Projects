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
        Schema::create('fleets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            // $table->string('thumb_img');
            $table->string('vehicle_id');
            $table->string('vehicle_type');
            $table->string('vehicle_class');
            $table->string('vehicle_num');
            $table->longText('vehicle_features');
            $table->string('color');
            $table->string('manufacturer');
            $table->string('model');
            $table->string('max_passengers');
            $table->string('max_luggage');
            $table->string('year_manufacturer');
            // $table->string('engine_size');
            // $table->string('miles_per_gallon');
            // $table->string('fuel_type');
            // $table->string('wheel_plan');
            // $table->string('emission_class');
            // $table->string('milage');
            $table->string('mot');
            // $table->string('school_contract');
            $table->string('vehicle_pco_license_pic');
            $table->string('vehicle_isurance_pic');
            $table->string('logbook_pic');
            $table->string('vehicle_mot_pic');
            $table->integer('active_status')->default(0)->comment('0=> Not active 1= Active');
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
        Schema::dropIfExists('fleets');
    }
};
