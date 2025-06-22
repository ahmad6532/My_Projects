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
        Schema::create('head_office_user_access_rights', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('head_office_user_id')->unsigned();
            $table->timestamps();

            $table->unique(['head_office_user_id'],'unique_head_office_user_id');
            $table->foreign('head_office_user_id')
                ->references('id')
                ->on('head_office_users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('head_office_user_access_rights');
    }
};
