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
        Schema::create('national_alert_groups', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('national_alert_id')->unsigned();
            $table->bigInteger('group_id')->unsigned();
            $table->timestamps();

            $table->foreign('national_alert_id')
                ->references('id')
                ->on('national_alerts')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('group_id')
                ->references('id')
                ->on('head_office_organisation_groups')
                ->onUpdate('cascade')
                ->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('national_alert_groups');
    }
};
