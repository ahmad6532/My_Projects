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
        Schema::create('organisation_setting_bespoke_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('o_s_id')->index();
            $table->unsignedBigInteger('be_spoke_form_id')->index();
            $table->timestamps();

            $table->foreign('o_s_id')
            ->references('id')
            ->on('organisation_settings')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            $table->foreign('be_spoke_form_id')
            ->references('id')
            ->on('be_spoke_form')
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
        Schema::dropIfExists('organisation_setting_bespoke_forms');
    }
};
