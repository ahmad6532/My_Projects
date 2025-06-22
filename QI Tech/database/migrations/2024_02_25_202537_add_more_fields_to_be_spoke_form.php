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
        Schema::table('be_spoke_form', function (Blueprint $table) {
            $table->string('purpose')->nullable();
            $table->enum('allow_editing_state',['disable','hour','day','week','always'])->default('always');
            $table->integer('allow_editing_time')->nullable();
            $table->boolean('allow_responder_update')->default(false);
            $table->integer('limits')->default(0)->nullable();
            $table->enum('expiry_state', ['never_expire', 'expiry_time']);
            $table->dateTime('expiry_time')->nullable();
            $table->enum('schedule_state', ['optional', 'day','date'])->default('day');
            $table->json('schedule_by_day')->nullable();
            $table->boolean('allow_drafts_off_site')->default(false);
            $table->boolean('show_submission_loc')->default(true);
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('be_spoke_form', function (Blueprint $table) {
            $table->dropColumn('purpose');
            $table->dropColumn('allow_editing_state');
            $table->dropColumn('allow_editing_time');
            $table->dropColumn('allow_responder_update');
            $table->dropColumn('limits');
            $table->dropColumn('expiry_state');
            $table->dropColumn('expiry_time');
            $table->dropColumn('schedule_state');
            $table->dropColumn('schedule_by_day');
            $table->dropColumn('allow_drafts_off_site');
            $table->dropColumn('show_submission_loc');
            //
        });
    }
};
