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
        Schema::create('assigned_bespoke_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('location_id')->index();
            $table->unsignedBigInteger('be_spoke_form_id')->index();
            $table->timestamps();

            $table->unique(['location_id', 'be_spoke_form_id'], 'abf_lid_bsf_id_unique');

            $table->foreign('location_id')
            ->on('locations')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('be_spoke_form_id')
            ->on('be_spoke_form')
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
        Schema::dropIfExists('assigned_bespoke_forms');
    }
};
