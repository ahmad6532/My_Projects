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
        Schema::create('five_whys_root_cause_analysis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('root_cause_analysis_id')->index();
            $table->text('question')->nullable();
            $table->timestamps();

            $table->foreign('root_cause_analysis_id')
            ->on('root_cause_analysis')
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
        Schema::dropIfExists('five_whys_root_cause_analysis');
    }
};
