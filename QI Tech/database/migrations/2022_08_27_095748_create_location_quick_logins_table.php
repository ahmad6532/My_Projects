<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLocationQuickLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_quick_logins', function(Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->integer('location_id')->unsigned()->nullable()->index();
            $table->string('pin')->nullable();
            $table->dateTime('last_login_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('location_quick_logins');
    }
}
