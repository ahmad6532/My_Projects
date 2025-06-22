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
        Schema::table('case_handler_users', function (Blueprint $table) {
            $table->dropForeign(['stage_id']);
        });

        Schema::table('case_handler_users', function (Blueprint $table) {
            $table->dropColumn('stage_id');
            $table->dropColumn('can_view_future_stages');
            $table->dropColumn('can_view_past_stages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('case_handler_users', function (Blueprint $table) {
            $table->dropColumn('stage_id');
        });

        Schema::table('case_handler_users', function (Blueprint $table) {
            $table->foreignId('stage_id')->nullable()->constrained('case_stages')->onDelete('cascade');
            $table->boolean('can_view_future_stages')->default(false);
            $table->boolean('can_view_past_stages')->default(false);
        });
    }
};
