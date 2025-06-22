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
        Schema::table('new_contacts', function (Blueprint $table) {
            //
            $table->boolean('is_deleted')->default(false);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_contacts', function (Blueprint $table) {
            //
            $table->dropColumn('is_deleted');
            $table->dropSoftDeletes();
        });
    }
};
