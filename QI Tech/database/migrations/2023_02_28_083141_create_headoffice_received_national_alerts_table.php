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
        Schema::create('head_office_received_national_alerts', function (Blueprint $table) {
            $table->id();
            $table->integer('head_office_id')->unsigned();
            $table->bigInteger('national_alert_id')->unsigned();
            $table->string('status')->default('draft');
            $table->dateTime('alert_date_time');
            $table->text('received_object_copy');
            $table->timestamps();

            $table->foreign('national_alert_id')
                ->references('id')
                ->on('national_alerts');

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
        Schema::dropIfExists('head_office_received_national_alerts');
    }
};
