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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('company_document_id')->nullable()->constrained('company_documents')->onDelete('cascade');
            $table->integer('user_id');
            $table->string('company_name');
            $table->string('company_email')->unique();
            // $table->string('password');
            $table->string('company_type');
            $table->string('company_address');
            $table->string('company_reg_num');
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_num')->nullable();
            $table->string('bank_sort_code')->nullable();
            $table->string('center_name')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('radius')->nullable();
            $table->string('average_ratings')->default(0);
            $table->integer('status')->default(0)->comment('0=> Not active 1= Active');
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
        Schema::dropIfExists('companies');
    }
};
