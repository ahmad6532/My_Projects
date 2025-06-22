<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLocationPasswordUpdateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_password_update_requests', function(Blueprint $table)
        {
            $table->increments('id');
           
            $table->integer('location_id')->unsigned()->index();
            $table->string('new_password', 70);
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
        Schema::drop('location_password_update_requests');
    }
}
