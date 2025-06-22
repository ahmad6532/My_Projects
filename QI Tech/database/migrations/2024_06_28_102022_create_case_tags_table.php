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
        Schema::create('case_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('head_office_id');
            $table->foreign('head_office_id')->references('id')->on('head_offices');
            $table->unsignedBigInteger('case_id');
            $table->foreign('case_id')->references('id')->on('head_office_cases');

            $table->string('name')->nullable();
            $table->string('color')->default('#000');
            $table->string('icon')->default('1');
            $table->string('icon_color')->default('#fff');
            $table->string('text_color')->default('#fff');
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
        Schema::dropIfExists('case_tags');
    }
};
