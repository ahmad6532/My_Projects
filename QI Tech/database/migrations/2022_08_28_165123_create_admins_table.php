<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function(Blueprint $table)
        {
            $table->increments('id');
           
            $table->string('first_name', 50);
            $table->string('surname', 50);
            $table->string('mobile_no', 20)->nullable();
            $table->string('email', 150);
            $table->string('password', 70);
            $table->timestamp('password_updated_at')->nullable();
            $table->boolean('is_active')->nullable();
            $table->rememberToken();
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
        Schema::drop('admins');
    }
}
