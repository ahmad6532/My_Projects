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
        Schema::table('system_links', function (Blueprint $table) {
            //
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('last_accessed_user')->nullable();
            $table->timestamp('last_accessed')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_links', function (Blueprint $table) {
            //
            $table->dropColumn('title');
            $table->dropColumn('description');
            $table->dropColumn('last_accessed_user');
            $table->dropColumn('last_accessed');
        });
    }
};
