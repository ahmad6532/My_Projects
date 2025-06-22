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
        Schema::table('be_spoke_form_questions', function (Blueprint $table) {
            $table->longText('question_extra_value')->nullable();
            $table->longText('question_extra_value_1')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('be_spoke_form_questions', function (Blueprint $table) {
            $table->dropColumn(['question_extra_value','question_extra_value_1']);
        });
    }
};
