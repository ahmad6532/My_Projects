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
        Schema::create('psa_actions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->bigInteger('received_alert_id')->unsigned();
            $table->string('action_type');
            $table->string('shared_this_alert')->default('no')->nullable();
            $table->string('shared_with_team')->nullable();

            # Read and changed practice.
            $table->string('have_defective_stock')->nullable();
            $table->float('defective_quantity')->nullable();
            $table->string('stock_been_quarantined')->nullable();
            $table->string('stock_been_quarantined_location')->nullable();
            $table->string('stock_been_quarantined_reason')->nullable();
            $table->string('stock_been_returned')->nullable();
            $table->string('stock_been_returned_reason')->nullable();
            $table->string('recall_awaiting_collection')->nullable();
            $table->string('patients_contacted')->nullable();
            $table->string('addtional_comments')->nullable();

            $table->timestamps();
            $table->unique(['user_id', 'received_alert_id']);
            
            $table->foreign('received_alert_id')
                ->references('id')
                ->on('location_received_alerts');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('psa_actions');
    }
};
