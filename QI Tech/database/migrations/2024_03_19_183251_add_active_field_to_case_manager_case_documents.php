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
        Schema::table('case_manager_case_documents', function (Blueprint $table) {
            $table->boolean('active')->default(true)->after('description');
            $table->unsignedBigInteger('uploaded_by')->nullable()->after('active');
            $table->unsignedBigInteger('updated_by')->nullable()->after('uploaded_by');
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
        Schema::table('case_manager_case_documents', function (Blueprint $table) {
            //
            $table->dropColumn('active');
            $table->dropColumn('uploaded_by');
            $table->dropColumn('updated_by');
        });
    }
};
