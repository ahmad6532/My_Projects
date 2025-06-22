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
        Schema::create('head_office_locations', function (Blueprint $table) {
            $table->id();
            $table->integer('head_office_id')->unsigned()->index();
            $table->integer('location_id')->unsigned()->index();
            $table->timestamps();

            $table->unique(['head_office_id','location_id']);

            $table->foreign('head_office_id')
                ->references('id')
                ->on('head_offices');


            $table->foreign('location_id')
                ->references('id')
                ->on('locations');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('head_office_locations');
    }
};
