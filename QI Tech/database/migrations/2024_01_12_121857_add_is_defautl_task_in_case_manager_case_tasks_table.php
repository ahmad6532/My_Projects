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
        Schema::table('case_manager_case_tasks', function (Blueprint $table) {
            $table->tinyInteger('is_default_task')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('case_manager_case_tasks', function (Blueprint $table) {
            $table->dropColumn(['is_default_task']);
        });
    }
};
