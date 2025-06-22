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
        Schema::create('gphc_pharmacists', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('gphc_registration_number')->unsigned();
            $table->string('surname')->nullable();
            $table->string('forenames')->nullable();
            $table->string('town')->nullable();
            $table->string('supplementary_prescriber')->nullable();
            $table->string('independent_prescriber')->nullable();
            $table->string('superintendent_pharmacist')->nullable();
            $table->string('status_description')->nullable();


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
        Schema::dropIfExists('gphc_pharmacists');
    }
};
