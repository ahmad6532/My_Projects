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
        Schema::create('form_modification_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_record_id');
            $table->foreign('parent_record_id')->references('id')->on('be_spoke_form_records')->onDelete('cascade');
            $table->unsignedBigInteger('modified_record_id');
            $table->foreign('modified_record_id')->references('id')->on('be_spoke_form_records')->onDelete('cascade');
            $table->unsignedInteger('head_office_id');
            $table->foreign('head_office_id')
            ->on('head_offices')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')
            ->on('users')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->unsignedInteger('location_id')->nullable();
            $table->foreign('location_id')
            ->on('locations')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            $table->json('modified_data')->nullable();
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
        Schema::dropIfExists('form_modification_logs');
    }
};
