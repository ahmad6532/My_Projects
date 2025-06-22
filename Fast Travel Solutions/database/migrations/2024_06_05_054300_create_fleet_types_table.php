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
        Schema::create('fleet_types', function (Blueprint $table) {
            $table->id();
            $table->string('type_name');
            $table->string('car_name');
            $table->string('car_picture');
            $table->string('car_icon');
            $table->integer('total_passengers');
            $table->integer('luggage_bags'); //i.e 1,2,3,4
            $table->integer('active_status')->default(1)->comment('0=> Not active 1= Active');
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
        Schema::dropIfExists('fleet_types');
    }
};
