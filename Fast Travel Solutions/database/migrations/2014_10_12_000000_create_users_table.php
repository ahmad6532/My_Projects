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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('profile_picture')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('company_user_role')->nullable();
            $table->string('phone')->nullable();
            $table->string('role_id')->default(1); 
            $table->integer('active_status')->default(1); 
            $table->integer('is_deleted')->default(0); 
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            // $table->string('otp')->nullable(); 
            $table->rememberToken();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
