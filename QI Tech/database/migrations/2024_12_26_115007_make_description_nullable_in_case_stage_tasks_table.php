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
    Schema::table('case_stage_tasks', function (Blueprint $table) {
        $table->text('description')->nullable()->change();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
{
    Schema::table('case_stage_tasks', function (Blueprint $table) {
        $table->text('description')->nullable(false)->change();
    });
}
};
