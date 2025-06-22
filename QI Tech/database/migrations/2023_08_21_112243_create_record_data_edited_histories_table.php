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
        Schema::create('record_data_edited_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id');
            $table->unsignedBigInteger('record_id');
            $table->unsignedBigInteger('record_data_id')->nullable();
            $table->unsignedInteger('updated_by'); //user id
            $table->string('old_value')->nullable();
            $table->string('updated_value')->nullable();
            $table->timestamps();

            $table->foreign('form_id')
            ->on('be_spoke_form')
            ->references('id')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            
            $table->foreign('record_id')
            ->on('be_spoke_form_records')
            ->references('id')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            
            $table->foreign('record_data_id')
            ->on('be_spoke_form_record_data')
            ->references('id')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            
            $table->foreign('updated_by')
            ->on('users')
            ->references('id')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('record_data_edited_histories');
    }
};
