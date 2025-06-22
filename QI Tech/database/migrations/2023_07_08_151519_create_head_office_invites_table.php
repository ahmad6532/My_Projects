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
        Schema::create('head_office_invites', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('head_office_position');
            $table->unsignedBigInteger('head_office_user_profile_id')->index();
            $table->unsignedBigInteger('invited_by_id')->nullable()->index();
            $table->string('invited_by_type');
            $table->timestamp('expires_at');
            $table->string('token');

            $table->foreign('head_office_user_profile_id')
            ->on('head_office_user_profiles')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');

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
        Schema::dropIfExists('head_office_invites');
    }
};
