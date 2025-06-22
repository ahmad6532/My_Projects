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
        Schema::create('dmds', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('APID')->nullable();;
            $table->bigInteger('VPID')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('SUPPCD')->nullable();
            $table->string('LIC_AUTHCD')->nullable();
            $table->string('AVAIL_RESTRICTCD')->nullable();
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
        Schema::dropIfExists('dmds');
    }
};
