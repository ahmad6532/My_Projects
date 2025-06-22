<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('module_id');
            $table->integer('selected_id');
            $table->string('selected_type');
            $table->integer('approval_level');
            $table->enum('bypass_approval',[0,1]);
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
        Schema::dropIfExists('approval_settings');
    }
}
