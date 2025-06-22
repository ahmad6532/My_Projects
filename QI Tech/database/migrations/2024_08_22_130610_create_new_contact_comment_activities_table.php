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
        Schema::create('new_contact_comment_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('type')->nullable();
            $table->string('action');
            $table->timestamp('timestamp')->default(now());
            $table->timestamps();
            $table->unsignedInteger('head_office_id')->nullable();
            $table->foreign('head_office_id')->references('id')->on('head_offices');
            $table->unsignedBigInteger('comment_id')->nullable();
            $table->foreign('comment_id')->on('new_contact_comments')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_contact_comment_activities');
    }
};
