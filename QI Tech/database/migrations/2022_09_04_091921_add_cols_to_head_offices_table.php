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
        Schema::table('head_offices', function (Blueprint $table) {
            //
            $table->string('bg_color_code', 10)->nullable();
            $table->string('font', 80)->nullable();
            $table->integer('password_updated_by_user_id')->index()->unsigned()->nullable();
            $table->timestamp('password_updated_at')->nullable();


            $table->foreign('password_updated_by_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
                ->onUpdate('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('head_offices', function (Blueprint $table) {

            $table->dropColumn('bg_color_code');
            $table->dropColumn( 'font');
                $table->dropForeign(['password_updated_by_user_id']);
            $table->dropColumn( 'password_updated_by_user_id');
            $table->dropColumn( 'password_updated_at');
        });
    }
};
