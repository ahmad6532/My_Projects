<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHeadOfficeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('head_office_requests', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('first_name', 50);
            $table->string('surname', 50);
            $table->string('organization', 80)->nullable();
            $table->string('position', 80)->nullable();
            $table->string('email', 140);
            $table->string('telephone_no', 20)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('email_verification_key', 70)->nullable();
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
        Schema::drop('head_office_requests');
    }
}
