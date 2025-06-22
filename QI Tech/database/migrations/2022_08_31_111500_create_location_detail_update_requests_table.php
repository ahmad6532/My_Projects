<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLocationDetailUpdateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_detail_update_requests', function(Blueprint $table)
        {
            $table->increments('id');
           
            $table->integer('location_id')->unsigned()->index();
            $table->string('trading_name', 80);
            $table->string('address_line1', 80);
            $table->string('address_line2', 50)->nullable();
            $table->string('address_line3', 50)->nullable();
            $table->string('registration_no', 50)->nullable();
            $table->string('telephone_no', 20)->nullable();
            $table->integer('status')->nullable();
            $table->integer('user_id')->unsigned()->index();
            $table->string('token', 70)->nullable();
            $table->timestamps();

            $table->foreign('location_id')
                  ->references('id')
                  ->on('locations')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
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
        Schema::drop('location_detail_update_requests');
    }
}
