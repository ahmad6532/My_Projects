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
        Schema::create('linked_cases', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('head_office_id');
            $table->foreign('head_office_id')->references('id')->on('head_offices');
            $table->unsignedBigInteger('case_id_1');
            $table->foreign('case_id_1')->references('id')->on('head_office_cases');
            $table->unsignedBigInteger('case_id_2');
            $table->foreign('case_id_2')->references('id')->on('head_office_cases');
            $table->text('message')->nullable();
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
        Schema::dropIfExists('linked_cases');
    }
};
