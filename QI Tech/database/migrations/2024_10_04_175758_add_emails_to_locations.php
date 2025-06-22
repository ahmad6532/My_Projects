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
        Schema::table('locations', function (Blueprint $table) {
            //
            $table->json('emails')->nullable();
            $table->json('phones')->nullable();
            $table->json('email_notes')->nullable();
            $table->json('phone_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            //
            $table->dropColumn('emails');
            $table->dropColumn('phones');
            $table->dropColumn('email_notes');
            $table->dropColumn('phone_notes');
        });
    }
};
