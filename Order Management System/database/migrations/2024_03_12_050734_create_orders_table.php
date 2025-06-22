<?php

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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('orderId');
            $table->string('productName');
            $table->integer('quantity');
            $table->foreignId('customerId')->references('id')->on('users');
            $table->foreignId('riderId')->references('id')->on('users');
            $table->enum('status', ['PENDING', 'ACCEPTED', 'PICKED', 'ON_MY_WAY', 'DELIVERED', 'COMPLETED']);
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
