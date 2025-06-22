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
        Schema::create('case_stages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id');
            $table->string('name');
            $table->tinyInteger('is_default');
            $table->tinyInteger('is_current_stage')->default(0);
            $table->integer('label')->nullable();
            $table->timestamps();

            $table->foreign('case_id')
            ->references('id')
            ->on('head_office_cases')
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
        Schema::dropIfExists('case_stages');
    }
};
