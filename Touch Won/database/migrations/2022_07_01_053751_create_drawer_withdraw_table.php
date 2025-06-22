<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrawerWithdrawTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drawer_withdraw', function (Blueprint $table) {
            $table->integer('id', true);
            $table->bigInteger('drawer_id')->index('drawer_withdraw_fk0');
            $table->float('withdraw_amount', 10, 0);
            $table->timestamp('withdraw_done_on')->useCurrentOnUpdate()->useCurrent();
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
        Schema::dropIfExists('drawer_withdraw');
    }
}
