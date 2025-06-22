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
        Schema::dropIfExists('alert_shared_with');
        Schema::dropIfExists('alert_documents');
        Schema::dropIfExists('national_alerts');
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('national_alerts', function(Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('title', 255)->nullable();
            $table->string('alert_type')->nullable();
            $table->string('summary')->nullable();

        });
        
        Schema::create('alert_documents', function(Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('national_alert_id')->unsigned()->nullable()->index();
            $table->string('alert_document')->nullable();

            $table->foreign('national_alert_id')
                  ->references('id')
                  ->on('national_alerts')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

        });
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
};
