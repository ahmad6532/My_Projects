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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->bigInteger('company_id')->nullable();
            $table->bigInteger('driver_id')->nullable();
            $table->string('title', 100);
            $table->string('description', 1000)->nullable();
            $table->string('image', 200)->nullable();
            $table->dateTime('entry_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('schedule_date')->nullable();
            $table->char('read_status', 1)->default('N');
            $table->string('read_date', 50)->nullable();
            $table->string('app_sent_date', 100)->nullable();
            $table->char('for_admin', 1)->default('N');
            $table->string('notification_type');
            $table->char('sent_status', 1)->default('N');
            $table->integer('campaign_entry')->default(0);
            $table->enum('device_type', ['iOS', 'android', 'all'])->default('all');
            $table->char('is_msg_app', 1)->default('N');
            $table->char('is_msg_sms', 1)->default('N');
            $table->char('is_msg_email', 1)->default('N');
            $table->text('user_notification')->nullable();
            $table->enum('is_notification_required', ['Y', 'N'])->default('N');
            $table->string('message_error', 500)->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));  

            // Define foreign keys if any
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('plant_id')->references('id')->on('plants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
