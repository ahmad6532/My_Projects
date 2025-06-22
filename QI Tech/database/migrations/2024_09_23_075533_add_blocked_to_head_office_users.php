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
        Schema::table('head_office_users', function (Blueprint $table) {
            //
            $table->boolean('is_blocked')->default(false);
            $table->string('block_comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('head_office_users', function (Blueprint $table) {
            //
            $table->dropColumn('is_blocked');
            $table->dropColumn('block_comment');
        });
    }
};
