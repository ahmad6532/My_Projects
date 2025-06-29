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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->timestamp('last_login_at')->nullable();
            $table->integer('last_login_location_id')->unsigned()->nullable()->index();
            
            $table->foreign('last_login_location_id')
                ->references('id')
                ->on('locations')
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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('last_login_at');
            $table->dropForeign(['last_login_location_id']);
            $table->dropColumn('last_login_location_id');
        });
    }
};
