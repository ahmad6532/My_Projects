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
        Schema::create('be_spoke_form_record_updates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('be_spoke_form_record_id')->index();
            $table->text('update');
            $table->timestamps();

            $table->foreign('be_spoke_form_record_id')
            ->on('be_spoke_form_records')
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
        Schema::dropIfExists('be_spoke_form_record_updates');
    }
};
