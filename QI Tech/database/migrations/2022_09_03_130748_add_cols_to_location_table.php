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
        Schema::table('locations', function (Blueprint $table) {
            $table->boolean('is_archived')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_suspended')->default(false);
            $table->timestamp('trial_started_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn( 'is_archived');
            $table->dropColumn( 'is_active');
            $table->dropColumn( 'is_suspended');
            $table->dropColumn( 'trial_started_at');
            $table->dropColumn( 'trial_ends_at');

        });
    }
};
