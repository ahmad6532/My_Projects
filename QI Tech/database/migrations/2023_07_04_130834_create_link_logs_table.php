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
        Schema::create('link_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('link_id');
            $table->timestamp('removal_date')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->text(('title'));
            $table->text('link');
            $table->timestamp('date_to_be_removed')->nullable();

            
            $table->tinyInteger('is_link_deleted')->nullable();
            
            $table->unsignedInteger(('user_id'));
            $table->timestamps();

            $table->foreign('link_id')
            ->on('links')
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
        Schema::dropIfExists('link_logs');
    }
};
