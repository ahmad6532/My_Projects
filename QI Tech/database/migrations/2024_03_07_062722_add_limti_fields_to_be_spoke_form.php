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
            $table->boolean('active_limit_by_amount')->default(true)->after('limits');
            $table->boolean('amount_total_max_res')->default(true)->after('active_limit_by_amount');
            $table->boolean('limit_to_one_user')->default(true)->after('amount_total_max_res');
            $table->boolean('limit_to_one_location')->default(false)->after('limit_to_one_user');
            $table->boolean('active_limit_by_period')->default(true)->after('limit_to_one_location');
            $table->enum('limit_by_period_max_state',['off','day','month','year'])->default('month')->after('limit_to_one_location');
            $table->integer('limit_by_period_max_value')->default(1)->after('limit_by_period_max_state');
            $table->enum('limit_by_period_min_state',['off','day','month','year'])->default('month')->after('limit_by_period_max_value');
            $table->integer('limit_by_period_min_value')->default(1)->after('limit_by_period_min_state');
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
        Schema::table('be_spoke_form', function (Blueprint $table) {
            $table->dropColumn('active_limit_by_amount');
            $table->dropColumn('amount_total_max_res');
            $table->dropColumn('limit_to_one_user');
            $table->dropColumn('limit_to_one_location');
            $table->dropColumn('active_limit_by_period');
            $table->dropColumn('limit_by_period_max_state');
            $table->dropColumn('limit_by_period_max_value');
            $table->dropColumn('limit_by_period_min_state');
            $table->dropColumn('limit_by_period_min_value');
            //
        });
    }
};
