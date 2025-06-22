<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('national_alerts', function (Blueprint $table) {
            $table->integer('class');
            $table->boolean('patient_level_recall');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('national_alerts', 'class')) {
            Schema::table('national_alerts', function (Blueprint $table) {
                $table->dropColumn(['class', 'patient_level_recall']);
            });
        }
    }
};