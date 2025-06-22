<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxSlabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_slabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('year_id');
            $table->foreign('year_id')->references('id')->on('tax_years')->onDelete('cascade');
            $table->bigInteger('start_range');
            $table->bigInteger('end_range');
            $table->bigInteger('fixed_amount');
            $table->bigInteger('amount_exceed');
            $table->float('tax_percent');
            $table->enum('is_deleted',['0','1'])->default('0');
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
        Schema::dropIfExists('tax_slabs');
    }
}
