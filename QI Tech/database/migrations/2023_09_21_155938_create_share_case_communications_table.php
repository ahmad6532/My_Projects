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
        Schema::create('share_case_communications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('share_case_id')->index();
            $table->text('message')->nullable();
            $table->tinyInteger('is_user')->default(0);
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->foreign('share_case_id')
            ->on('share_cases')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('user_id')
            ->on('users')
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
        Schema::dropIfExists('share_case_communications');
    }
};
