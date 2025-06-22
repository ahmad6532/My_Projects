<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('company_id', 11);
            $table->string('branch_id', 11);
            $table->text('event_name');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('is_repeated', ['0', '1'])->default('0');
            $table->enum('is_active', ['0', '1'])->nullable();
            $table->enum('is_deleted', ['0', '1'])->default('0');

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
        Schema::dropIfExists('holidays');
    }
}
