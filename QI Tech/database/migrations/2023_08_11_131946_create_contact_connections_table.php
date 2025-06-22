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
        Schema::create('contact_connections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_id')->index();
            $table->unsignedBigInteger('connected_with_id');
            $table->string('relation_type')->nullable();
            $table->timestamps();

            $table->unique(['contact_id','connected_with_id']);

            $table->foreign('contact_id')
            ->on('contacts')
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
        Schema::dropIfExists('contact_connections');
    }
};
