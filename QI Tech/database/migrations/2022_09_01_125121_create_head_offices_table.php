<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHeadOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('head_offices', function(Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->string('company_name', 100);
            $table->string('address', 150);
            $table->string('telephone_no', 20);
            $table->string('email')->unique();
            $table->string('password', 70);
            $table->integer('status')->nullable();
            $table->integer('last_login_user_id')->unsigned()->nullable()->index();
            $table->dateTime('last_login_at')->nullable();

            $table->foreign('last_login_user_id')
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
        Schema::drop('head_offices');
    }
}
