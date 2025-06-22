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
        Schema::create('case_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('head_office_id');
            $table->foreign('head_office_id')->references('id')->on('head_offices')->onDelete('cascade');
            $table->unsignedInteger('reported_by_user_id');
            $table->foreign('reported_by_user_id')->references('id')->on('users');
            $table->unsignedInteger('feedback_by_user_id');
            $table->foreign('feedback_by_user_id')->references('id')->on('users');
            $table->unsignedInteger('location_id')->nullable();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->json('case_ids')->nullable();
            $table->boolean('is_feedback_user')->default(false);
            $table->text('feedback_user')->nullable();
            $table->boolean('is_feedback_location')->default(false);
            $table->text('feedback_location')->nullable();
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
        Schema::dropIfExists('case_feedbacks');
    }
};
