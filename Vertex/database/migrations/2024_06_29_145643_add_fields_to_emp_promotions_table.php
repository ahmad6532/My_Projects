<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToEmpPromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    Schema::table('emp_promotions', function (Blueprint $table) {
        $table->renameColumn('emp_desig', 'designation_from');
        $table->renameColumn('designation_id', 'designation_to');
        $table->decimal('current_salary', 10, 2)->after('designation_id')->nullable();
        $table->decimal('increment', 10, 2)->after('current_salary')->nullable();
        $table->decimal('incremented_salary', 10, 2)->after('increment')->nullable();
        $table->enum('is_approved',['0','1','2'])->after('incremented_salary');

    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('emp_promotions', function (Blueprint $table) {
            $table->renameColumn('designation_from', 'emp_desig');
            $table->renameColumn('designation_to', 'designation_id');
            $table->dropColumn('current_salary');
            $table->dropColumn('increment');
            $table->dropColumn('incremented_salary');
            $table->dropColumn('is_approved');
         });
    }
}
