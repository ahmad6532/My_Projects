<?php

use App\Enums\UserStatusEnum;
use App\Enums\UserGenderEnum;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstName');
            $table->string('lastName');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('uuid')->nullable();
            $table->string('phone');
            $table->enum('gender',[UserGenderEnum::MALE,UserGenderEnum::FEMALE])->default(UserGenderEnum::MALE);
            $table->foreignId('managerId')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->string('avatar');
            $table->enum('status', [UserStatusEnum::ACTIVE, UserStatusEnum::INACTIVE])->default(UserStatusEnum::ACTIVE);
            $table->string('address');
            $table->string('country');
            $table->integer('postalCode');
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
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
