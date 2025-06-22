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
        Schema::create('dmd_vtms', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('VTMID');
            $table->string('NM');
            $table->string('VTMIDPREV')->nullable();
            $table->timestamp('VTMIDDT')->nullable();
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
        Schema::dropIfExists('dmd_vtms');
    }
};
