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
        Schema::create('head_office_orginisation_levels', function (Blueprint $table) {
            $table->id();
            $table->integer('head_office_id')->unsigned();
            $table->integer('level_number');
            $table->string('level_name')->nullable();
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
        Schema::dropIfExists('head_office_orginisation_levels');
    }
};
