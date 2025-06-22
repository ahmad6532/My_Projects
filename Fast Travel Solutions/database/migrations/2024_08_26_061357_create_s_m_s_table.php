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
        Schema::create('s_m_s', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('phone_number', 200);
            $table->text('sms_body')->nullable();
            $table->string('from_phone_number', 200)->nullable();
            $table->timestamp('sms_schedule_date')->nullable();
            $table->enum('sms_sent_status', ['Y', 'N'])->default('N');
            $table->integer('campaign_entry')->default(0);
            $table->text('response')->nullable();
            $table->timestamp('sent_date')->useCurrent();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_m_s');
    }
};
