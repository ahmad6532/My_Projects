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
        Schema::create('f_a_q_s', function (Blueprint $table) {
            $table->id();
            // $table->string('category_name');
            $table->foreignId('section_name_id')->constrained('faq_section_names')->onDelete('cascade');
            $table->longText('question')->nullable();
            $table->longText('answer')->nullable();
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
        Schema::dropIfExists('f_a_q_s');
    }
};
