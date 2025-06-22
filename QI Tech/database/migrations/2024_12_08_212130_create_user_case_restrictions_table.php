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
        Schema::create('user_case_restrictions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ho_user_id');
            $table->foreign('ho_user_id')->references('id')->on('head_office_users')->onDelete('cascade');
            $table->unsignedBigInteger('case_id');
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
        Schema::dropIfExists('user_case_restrictions');
    }
};
