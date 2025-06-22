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
        Schema::create('case_request_informations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id');
            $table->tinyInteger('status')->default(0);
            $table->text('note')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('email')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('is_available_to_person')->default(0);
            $table->timestamps();

            $table->foreign('case_id')
            ->on('head_office_cases')
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
        Schema::dropIfExists('case_request_informations');
    }
};
