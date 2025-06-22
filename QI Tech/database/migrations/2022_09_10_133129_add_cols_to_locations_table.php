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
        Schema::table('locations', function (Blueprint $table) {
            $table->integer('last_login_user_id')->unsigned()->nullable()->index();
            $table->dateTime('last_login_at')->nullable();

            $table->foreign('last_login_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
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
        Schema::table('locations', function (Blueprint $table) {
            $table->dropForeign(['last_login_user_id']);
            $table->dropColumn('last_login_user_id');
            $table->dropColumn('last_login_at');
        });
    }
};
