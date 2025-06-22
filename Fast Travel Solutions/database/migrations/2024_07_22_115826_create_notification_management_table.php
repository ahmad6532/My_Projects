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
        Schema::create('notification_management', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50);
            $table->integer('is_hidden')->default(0);
            $table->enum('user_type', ['user', 'operator','admin'])->nullable();
            $table->string('sms', 200)->nullable();
            $table->text('mail')->nullable();
            $table->string('mobile_app_title', 50)->nullable();
            $table->text('mobile_app_description')->nullable();
            $table->enum('send_sms', ['Y', 'N'])->default('N');
            $table->enum('send_email', ['Y', 'N'])->default('N');
            $table->text('mail_subject')->nullable();
            $table->string('to_email', 50)->nullable();
            $table->text('header')->nullable();
            $table->text('footer')->nullable();
            $table->enum('send_app_noti', ['Y', 'N'])->default('N');
            $table->string('variable_list', 100)->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));    

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_management');
    }
};
