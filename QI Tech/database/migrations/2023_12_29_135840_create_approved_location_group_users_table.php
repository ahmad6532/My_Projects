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
        Schema::create('approved_location_group_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('head_office_id');
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('head_office_organisation_group_id');
            $table->timestamps();

            $table->foreign('head_office_id')
            ->on('head_offices')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            $table->foreign('user_id')
            ->on('users')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign(['head_office_organisation_group_id'],'hoog_id')
            ->on('head_office_organisation_groups')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->unique(['head_office_id','user_id','head_office_organisation_group_id'],'houog_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approved_location_group_users');
    }
};
