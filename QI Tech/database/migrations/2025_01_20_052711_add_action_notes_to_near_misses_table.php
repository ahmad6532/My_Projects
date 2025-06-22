<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActionNotesToNearMissesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('near_misses', function (Blueprint $table) {
            $table->text('action_notes')->nullable()->after('error'); // Add column after 'error'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('near_misses', function (Blueprint $table) {
            $table->dropColumn('action_notes');
        });
    }
}
