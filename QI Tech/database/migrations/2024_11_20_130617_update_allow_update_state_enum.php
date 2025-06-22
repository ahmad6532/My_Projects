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
            // Drop the existing column
            $table->dropColumn('allow_update_state');
        });

        Schema::table('be_spoke_form', function (Blueprint $table) {
            // Add the column back with updated enum values
            $table->enum('allow_update_state', ['disable', 'minutes', 'hour', 'day', 'week', 'always'])
                ->default('always');
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
            // Drop the updated column
            $table->dropColumn('allow_update_state');
        });

        Schema::table('be_spoke_form', function (Blueprint $table) {
            // Add the original column back
            $table->enum('allow_update_state', ['disable', 'hour', 'day', 'week', 'always'])
                ->default('always');
        });
    }
};
