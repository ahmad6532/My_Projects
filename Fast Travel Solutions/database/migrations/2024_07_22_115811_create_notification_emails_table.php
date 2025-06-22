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
        Schema::create('notification_emails', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('driver_id')->nullable();
            $table->string('to_email', 200);
            $table->string('email_subject', 400);
            $table->text('email_body')->nullable();
            $table->string('from_email', 200)->nullable();
            $table->string('cc_email', 200)->nullable();
            $table->string('bcc_email', 200)->nullable();
            $table->timestamp('schedule_date')->nullable();
            $table->enum('email_type', ['Custom', 'Booking', 'Update-Booking', 'Close-Booking'])->default('Custom');
            $table->enum('email_sent_status', ['Y', 'N'])->default('N');
            $table->integer('campaign_entry')->default(0);
            $table->text('response')->nullable();
            $table->string('from_name', 200)->nullable();
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
        Schema::dropIfExists('notification_emails');
    }
};
