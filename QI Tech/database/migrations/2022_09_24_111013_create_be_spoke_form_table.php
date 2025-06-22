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
        Schema::create('be_spoke_form', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamp('fields_updated_at')->nullable();
            $table->json('stages');
            $table->enum('type',['Dispensing Incident','Near Miss'])->nullable();
            $table->boolean('is_active')->default(false);
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
        Schema::dropIfExists('be_spoke_form');
    }
};
