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
        Schema::create('gphc_technicians', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('gphc_registration_number')->unsigned();
            $table->string('surname')->nullable();
            $table->string('forenames')->nullable();
            $table->string('town')->nullable();
            $table->string('status')->nullable();
            $table->string('expiry_date')->nullable();
            $table->string('fitness_to_practise_issues')->nullable();

            $table->bigInteger('database_id')->unsigned();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gphc_technicians');
    }
};
