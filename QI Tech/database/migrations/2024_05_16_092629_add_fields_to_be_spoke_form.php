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
            //
            $table->renameColumn('feed_back_location_when_case_closed_in_be_spoke_form', 'is_case_close_priority');
            $table->string('case_close_priority_rule')->nullable()->after('feed_back_location_when_case_closed_in_be_spoke_form');
            $table->integer('case_close_priority_value')->nullable()->after('case_close_priority_rule');
            $table->text('case_close_priority_comment')->nullable()->after('case_close_priority_value');
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
            //
            $table->renameColumn('is_case_close_priority', 'feed_back_location_when_case_closed_in_be_spoke_form');
            $table->dropColumn('case_close_priority_rule');
            $table->dropColumn('case_close_priority_value');
            $table->dropColumn('case_close_priority_comment');
        });
    }
};
