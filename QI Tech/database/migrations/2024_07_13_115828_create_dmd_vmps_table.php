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
        Schema::create('dmd_vmps', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('VPID');
            $table->bigInteger('VTMID')->nullable();
            $table->string('NM');
            $table->string('ABBREVNM')->nullable();
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
        Schema::dropIfExists('dmd_vmps');
    }
};
