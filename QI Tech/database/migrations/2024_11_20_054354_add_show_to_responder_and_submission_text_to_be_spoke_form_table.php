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
        Schema::table('be_spoke_form', function (Blueprint $table) {
            $table->text('submission_text')->nullable(); // To store the customized text
            $table->boolean('show_to_responder')->default(false); // To indicate if it should be shown to the responder
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('be_spoke_form', function (Blueprint $table) {
            $table->dropColumn('submission_text');
            $table->dropColumn('show_to_responder');
        });
    }
};
