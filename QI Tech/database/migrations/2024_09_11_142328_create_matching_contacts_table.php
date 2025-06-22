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
        Schema::create('matching_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_1')->constrained('new_contacts')->onDelete('cascade');
            $table->foreignId('contact_2')->constrained('new_contacts')->onDelete('cascade');
            $table->float('match')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('matching_contacts');
    }
};
