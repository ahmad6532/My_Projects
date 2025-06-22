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
        Schema::create('alert_shared_with', function (Blueprint $table) {
            $table->id();
            $table->integer('alert_id')->unsigned()->index();

            $table->foreign('alert_id')
                ->references('id')
                ->on('national_alerts')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->integer('location_user_id')->unsigned()->index();
            $table->foreign('location_user_id')
                ->references('id')
                ->on('location_quick_logins')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alert_shared_with');
    }
};
