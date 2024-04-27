<?php

use App\Enums\PlanStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id('subscriptionId');
            $table->foreignId('userId')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('planId')->references('planId')->on('plans')->onDelete('cascade');
            $table->enum('status',[PlanStatusEnum::PAID,PlanStatusEnum::PENDING])->default(PlanStatusEnum::PENDING);
            $table->integer('articles');
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
