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
        Schema::create('share_case_communications_views', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('head_office_user_id')->unsigned();
            $table->bigInteger('comment_id')->unsigned();
            $table->foreign('comment_id')
                ->references('id')
                ->on('share_case_communications')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('head_office_user_id')
                ->references('id')
                ->on('head_office_users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
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
        Schema::dropIfExists('share_case_communications_views');
    }
};
