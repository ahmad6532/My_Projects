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
        Schema::create('contact_to_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained('new_contacts')->onDelete('cascade');
            $table->unsignedBigInteger('case_id')->nullable();
            $table->foreign('case_id')->references('id')->on('head_office_cases')->onDelete('cascade');
            $table->softDeletes();
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
        Schema::dropIfExists('contact_to_cases');
    }
};
