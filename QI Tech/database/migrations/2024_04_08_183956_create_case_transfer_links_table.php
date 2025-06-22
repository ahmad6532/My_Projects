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
        Schema::create('case_transfer_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('head_office_id');
            $table->foreign('head_office_id')->references('id')->on('head_offices');
            $table->json('case_ids')->nullable();
            $table->json('track_cases')->nullable();
            $table->string('link_token')->nullable();
            $table->timestamp('link_expiry')->nullable();
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
        Schema::dropIfExists('case_transfer_links');
    }
};
