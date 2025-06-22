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
        Schema::create('share_case_request_extensions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('share_case_id');
            $table->timestamp('extension_time');
            $table->tinyInteger('status')->nullable();// 0 for rejected 1 accepted
            $table->timestamps();

            $table->foreign('share_case_id')
            ->on('share_cases')
            ->references('id')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('share_case_request_extensions');
    }
};
