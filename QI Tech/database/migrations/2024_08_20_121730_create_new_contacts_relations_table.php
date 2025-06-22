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
        Schema::create('new_contacts_relations', function (Blueprint $table) {
            $table->id();
            $table->text("relation")->nullable();
            $table->text("reverse_relation")->nullable();
            $table->unsignedBigInteger('source_contact_id');
            $table->foreign('source_contact_id')->references('id')->on('new_contacts')->onDelete('cascade');
            $table->unsignedBigInteger('target_contact_id');
            $table->foreign('target_contact_id')->references('id')->on('new_contacts')->onDelete('cascade');
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
        Schema::dropIfExists('new_contacts_relations');
    }
};




