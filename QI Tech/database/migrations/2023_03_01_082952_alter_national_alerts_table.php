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
        Schema::table('national_alerts', function (Blueprint $table) {
            $table->bigInteger('admin_id')->nullable()->change();
            $table->string('send_to_groups')->nullable();
            $table->bigInteger('parent_id')->nullable();
        });
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('national_alerts', function (Blueprint $table) {
            $table->bigInteger('admin_id')->change();
            $table->dropColumn(['send_to_groups','parent_id']);
        });
       
    }
};
