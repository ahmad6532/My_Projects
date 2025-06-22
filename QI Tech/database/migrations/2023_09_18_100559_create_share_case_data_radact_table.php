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
        Schema::create('share_case_data_radacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('data_id');
            $table->tinyInteger('is_radact');
            $table->unsignedBigInteger('share_case_id');
            $table->timestamps();

            $table->foreign('data_id')
            ->on('be_spoke_form_record_data')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('share_case_id')
            ->on('share_cases')
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
        Schema::dropIfExists('share_case_data_radacts');
    }
};
