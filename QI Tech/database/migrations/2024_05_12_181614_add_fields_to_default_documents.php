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
        Schema::table('default_documents', function (Blueprint $table) {
            //
            $table->boolean('active')->default(true)->after('description');
            $table->boolean('from_case_log')->default(false)->after('active');
            $table->unsignedBigInteger('uploaded_by')->nullable()->after('active');
            $table->unsignedBigInteger('updated_by')->nullable()->after('uploaded_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('default_documents', function (Blueprint $table) {
            //
            $table->dropColumn('active');
            $table->dropColumn('from_case_log');
            $table->dropColumn('uploaded_by');
            $table->dropColumn('updated_by');
        });
    }
};
