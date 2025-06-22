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
        Schema::create('case_interested_parties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('head_office_user_id');
            $table->unsignedBigInteger('case_id');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('head_office_user_id')
            ->on('head_office_users')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('case_id')
            ->on('head_office_cases')
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
        Schema::dropIfExists('case_interested_parties');
    }
};
