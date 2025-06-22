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
        Schema::create('share_cases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('shared_by');
            $table->string('email');
            $table->tinyInteger('is_viewable')->default(0);
            $table->timestamp('duration_of_access');
            $table->tinyInteger('is_radact')->default(0);
            $table->tinyInteger('is_revoked')->default(0);
            $table->timestamps();
            
            $table->foreign('case_id')
            ->on('head_office_cases')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('shared_by')
            ->on('users')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');
                   
            $table->foreign('user_id')
            ->on('users')
            ->references('id')
            ->onDelete('cascade')
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
        Schema::dropIfExists('share_cases');
    }
};
