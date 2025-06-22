<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table)
        {
            $table->increments('id');
            
            $table->integer('position_id')->unsigned()->index();
            $table->boolean('is_registered')->nullable();
            $table->string('registration_no', 50)->nullable();
            $table->integer('location_regulatory_body_id')->unsigned()->nullable()->index();
            $table->string('country_of_practice', 80)->nullable();
            $table->string('first_name', 50);
            $table->string('surname', 50);
            $table->string('mobile_no', 20);
            $table->string('email', 150)->unique();
            $table->string('password', 70);
            $table->timestamp('password_updated_at')->nullable();
            $table->string('email_verification_key', 70)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('position_id')
                  ->references('id')
                  ->on('positions')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('location_regulatory_body_id')
                  ->references('id')
                  ->on('location_regulatory_bodies')
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
        Schema::drop('users');
    }
}
