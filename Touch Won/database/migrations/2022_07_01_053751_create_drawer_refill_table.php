<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrawerRefillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drawer_refill', function (Blueprint $table) {
            $table->integer('id', true);
            $table->bigInteger('drawer_id')->index('drawer_refill_fk0');
            $table->float('refill_amount', 10, 0);
            $table->timestamp('refill_done_on')->useCurrentOnUpdate()->useCurrent();
            $table->date('match_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drawer_refill');
    }
}
