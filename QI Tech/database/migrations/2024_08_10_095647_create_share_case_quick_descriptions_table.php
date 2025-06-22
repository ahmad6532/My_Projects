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
        Schema::create('share_case_quick_descriptions', function (Blueprint $table) {
            $table->id();
            $table->text("description")->nullable();
            $table->unsignedBigInteger('form_id');
            $table->foreign('form_id')->references('id')->on('be_spoke_form')->onDelete('cascade');
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
        Schema::dropIfExists('share_case_quick_descriptions');
    }
};
