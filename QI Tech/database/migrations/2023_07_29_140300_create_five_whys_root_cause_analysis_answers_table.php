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
        Schema::create('five_whys_root_cause_analysis_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('five_whys_root_cause_analysis_id');
            $table->text('answer')->nullable();
            $table->timestamps();

            $table->foreign(['five_whys_root_cause_analysis_id'],'fyrca_id')
            ->on('five_whys_root_cause_analysis')
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
        Schema::dropIfExists('five_whys_root_cause_analysis_answers');
    }
};
