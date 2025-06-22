<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function(Blueprint $table)
        {
            $table->increments('id');
            
            $table->integer('location_type_id')->unsigned()->index();
            $table->integer('location_pharmacy_type_id')->unsigned()->nullable()->index;
            $table->integer('location_regulatory_body_id')->unsigned()->index();
            $table->string('registered_company_name', 80);
            $table->string('trading_name', 80);
            $table->string('registration_no', 40);
            $table->string('address_line1', 100);
            $table->string('address_line2', 50)->nullable();
            $table->string('address_line3', 50)->nullable();
            $table->string('town', 50);
            $table->string('county', 50);
            $table->string('country', 80);
            $table->string('postcode', 30);
            $table->string('telephone_no', 20);
            $table->string('email', 240)->unique();
            $table->string('password', 80);
            $table->string('email_verification_key', 70)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('location_type_id')
                  ->references('id')
                  ->on('location_types')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('location_pharmacy_type_id')
                  ->references('id')
                  ->on('location_pharmacy_types')
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
        Schema::drop('locations');
    }
}
