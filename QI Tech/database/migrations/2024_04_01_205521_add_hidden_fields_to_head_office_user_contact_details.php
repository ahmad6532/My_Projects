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
        Schema::table('head_office_user_contact_details', function (Blueprint $table) {
            $table->boolean('is_email_hidden')->default(false);
            $table->boolean('is_phone_hidden')->default(false);
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('head_office_user_contact_details', function (Blueprint $table) {
            $table->dropColumn('is_email_hidden');
            $table->dropColumn('is_phone_hidden');
            //
        });
    }
};
