<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organisation_setting_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('location_id')->index()->unique();
            $table->unsignedInteger('o_s_id')->index();

            $table->timestamps();

            $table->foreign('location_id')
                ->references('id')
                ->on('locations')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('o_s_id')
                ->references('id')
                ->on('organisation_settings')
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
        Schema::dropIfExists('organisation_setting_assignments');
    }
};