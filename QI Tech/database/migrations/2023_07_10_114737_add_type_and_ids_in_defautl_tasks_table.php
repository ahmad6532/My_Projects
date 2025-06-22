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
        Schema::table('default_tasks', function (Blueprint $table) {
            $table->tinyInteger('type')->default(0);
            $table->string('type_ids')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('default_tasks', function (Blueprint $table) {
            $table->dropColumn(['type','type_ids']);
        });
    }
};
