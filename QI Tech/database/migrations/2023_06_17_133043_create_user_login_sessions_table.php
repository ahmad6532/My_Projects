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
        Schema::create('user_login_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->index();
            $table->string('country')->nullable();
            $table->string('ip')->nullable();
            $table->string('browser')->nullable();
            $table->string('city')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('is_active')->default(0);
            $table->string('user_session')->nullable();
            $table->tinyInteger('is_head_office')->default(0);
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
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
        Schema::dropIfExists('user_login_sessions');
    }
};
