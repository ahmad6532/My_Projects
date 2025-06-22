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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('head_office_id');
            $table->string('title')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('nhs_number')->nullable();
            $table->string('registration_no')->nullable();
            $table->string('company')->nullable();
            $table->string('website')->nullable();
            $table->string('profession')->nullable();
            $table->string('practice_name')->nullable();
            $table->timestamp('date_of_birth')->nullable();
            $table->string('email_address')->nullable();
            $table->string('gender')->nullable();
            $table->string('telephone_no')->nullable();
            $table->text('note')->nullable();
            
            $table->timestamps();

            $table->foreign('head_office_id')
            ->on('head_offices')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
};
