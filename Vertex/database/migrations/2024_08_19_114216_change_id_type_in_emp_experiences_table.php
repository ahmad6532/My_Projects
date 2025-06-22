<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIdTypeInEmpExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emp_experiences', function (Blueprint $table) {
            $table->dropPrimary(['id']);
            $table->unsignedInteger('id', true)->change();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emp_experiences', function (Blueprint $table) {
            $table->dropPrimary(['id']);
            $table->unsignedTinyInteger('id', true)->change();
            $table->primary('id');
        });
    }
}
