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
        Schema::create('be_spoke_form_lfpse_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('be_spoke_form_records_id')->index()->nullable();
            $table->string("lfpse_id",60);
            $table->string("version",10)->nullable();
            $table->string("outcome_type",20)->nullable();
            $table->string("remarks", 190)->nullable();
            $table->timestamps();

            $table->foreign('be_spoke_form_records_id')
            ->on('be_spoke_form_records')
            ->references('id')
            ->onDelete('set null')
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
        Schema::dropIfExists('be_spoke_form_lfpse_submissions');
    }
};
