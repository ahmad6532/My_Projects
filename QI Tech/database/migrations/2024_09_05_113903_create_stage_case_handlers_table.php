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
        Schema::create('stage_case_handlers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_handler_id')->constrained('case_handler_users')->onDelete('cascade');
            $table->foreignId('stage_id')->constrained('case_stages')->onDelete('cascade');
            $table->boolean('can_view_future_stages')->default(false);
            $table->boolean('can_view_past_stages')->default(false);
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
        Schema::dropIfExists('stage_case_handlers');
    }
};
