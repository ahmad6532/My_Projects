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
        Schema::create('calender_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->nullable()->constrained('be_spoke_form')->onDelete('set null');
            $table->string('title')->nullable();
            $table->boolean('active')->default(true);
            $table->enum('repeat_state',['off','year','month'])->default('month');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->json('times')->nullable();
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
        Schema::dropIfExists('calender_events');
    }
};
