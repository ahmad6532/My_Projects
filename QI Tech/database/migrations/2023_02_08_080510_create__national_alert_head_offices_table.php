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
        Schema::create('national_alert_head_offices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('national_alert_id')->unsigned();
            $table->integer('head_office_id')->unsigned();
            $table->timestamps();

            $table->foreign('national_alert_id')
                ->references('id')
                ->on('national_alerts')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('head_office_id')
                ->references('id')
                ->on('head_offices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('national_alert_head_offices');
    }
};
