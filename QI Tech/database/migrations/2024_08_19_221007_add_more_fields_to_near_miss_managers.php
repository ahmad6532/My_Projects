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
        Schema::table('near_miss_managers', function (Blueprint $table) {
            //
            $table->text('description')->nullable();
            $table->enum('allow_editing_state',['disable','minutes','hour','day','week','always'])->default('always');
            $table->integer('allow_editing_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('near_miss_managers', function (Blueprint $table) {
            //
            $table->dropColumn('description');
            $table->dropColumn('allow_editing_state');
            $table->dropColumn('allow_editing_time');
        });
    }
};
