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
        Schema::create('head_office_user_incident_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('head_office_user_id');
            $table->unsignedBigInteger('be_spoke_form_id');
            $table->string('location_id');
            $table->tinyInteger('is_email')->default(0)->nullable();
            $table->tinyInteger('is_share_cases')->default(0)->nullable();
            $table->tinyInteger('is_close_cases')->default(0)->nullable();
            $table->tinyInteger('is_statement_request')->default(0)->nullable();
            $table->tinyInteger('is_rca_request')->default(0)->nullable();
            $table->tinyInteger('is_read_only')->default(0)->nullable();
            $table->integer('min_prority')->default(0)->nullable();
            $table->integer('max_prority')->default(0)->nullable();
            $table->tinyInteger('is_active')->default(0)->nullable();
            $table->timestamps();
            

            $table->foreign('head_office_user_id')
            ->on('head_office_users')
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
        Schema::dropIfExists('head_office_user_incident_settings');
    }
};
