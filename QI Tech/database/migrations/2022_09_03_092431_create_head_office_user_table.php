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
        Schema::create('head_office_users', function (Blueprint $table) {
            $table->id();

            $table->integer('user_id')->unsigned()->index();
            $table->integer('head_office_id')->unsigned()->index();

            $table->integer('level')->nullable();
            $table->timestamps();
            $table->unique(['user_id','head_office_id']);



            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');


            $table->foreign('head_office_id')
                ->references('id')
                ->on('head_offices')
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
        Schema::dropIfExists('head_office_users');
    }
};
