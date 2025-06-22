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
        Schema::create('head_office_users_profile_assigns', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_profile_id')->unsigned();
            $table->bigInteger('head_office_user_id')->unsigned()->unique(); // One user of a one head office cannot get multiple profile assigned ! 1-1 relation
            $table->timestamps();
            
            //$table->unique(['user_profile_id','head_office_user_id'],'unique_user_profile_assignment'); ! we have now 1-1
            
            $table->foreign('user_profile_id')
                ->references('id')
                ->on('head_office_user_profiles')
                ->onUpdate('cascade')
                ->onDelete('cascade');

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
        Schema::dropIfExists('head_office_users_profile_assigns');
    }
};
