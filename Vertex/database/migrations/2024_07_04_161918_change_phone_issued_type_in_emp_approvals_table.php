<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePhoneIssuedTypeInEmpApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emp_approvals', function (Blueprint $table) {
            $table->string('phone_issued', 15)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emp_approvals', function (Blueprint $table) {
            $table->integer('phone_issued')->change();
        });
    }
}
