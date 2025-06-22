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
        Schema::create('head_office_user_profiles', function (Blueprint $table) {
            $table->id();
            $table->integer('head_office_id')->unsigned();
            $table->string('profile_name');
            # is created by system as default profile, prevent deletion of this one.
            $table->boolean('system_default_profile')->nullable()->default(0);
            # Has access to everything
            $table->boolean('super_access')->nullable()->default(0);
            $table->timestamps();

            $table->foreign('head_office_id')
                ->references('id')
                ->on('head_offices')
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
        Schema::dropIfExists('head_office_user_profiles');
    }
};
