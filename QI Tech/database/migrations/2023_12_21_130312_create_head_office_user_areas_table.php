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
        Schema::create('head_office_user_areas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('head_office_user_id');
            $table->string('area');
            $table->string('level');
            $table->timestamps();

            $table->foreign('head_office_user_id')
            ->on('head_office_users')
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
        Schema::dropIfExists('head_office_user_areas');
    }
};
