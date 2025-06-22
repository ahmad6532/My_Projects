<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorDrawerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_drawer', function (Blueprint $table) {
            $table->bigInteger('drawer_id', true);
            $table->bigInteger('vendor_id')->index('vendor_drawer_fk0');
            $table->timestamp('drawer_started_on')->nullable();
            $table->timestamp('drawer_ended_on')->nullable();
            $table->date('match_date')->nullable();
            $table->integer('is_active')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_drawer');
    }
}
