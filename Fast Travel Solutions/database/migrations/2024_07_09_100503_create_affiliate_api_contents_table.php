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
        Schema::create('affiliate_api_contents', function (Blueprint $table) {
            $table->id();
            $table->longText('state_1_text');
            $table->longText('state_2_text');
            $table->longText('state_3_text');
            $table->longText('offer_to_customer');
            $table->longText('why_fts');
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
        Schema::dropIfExists('affiliate_api_contents');
    }
};
