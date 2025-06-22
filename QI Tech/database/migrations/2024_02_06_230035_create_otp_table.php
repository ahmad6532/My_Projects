<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otp', function (Blueprint $table) {
            $table->id();
            $table->morphs('user');
            $table->integer('otp_code')->nullable();
            $table->timestamp('otp_created_at')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->integer('otp_retries')->default(3);
            $table->boolean('isVerified')->default(false);
            $table->boolean('isEnabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('otp');
    }
};
