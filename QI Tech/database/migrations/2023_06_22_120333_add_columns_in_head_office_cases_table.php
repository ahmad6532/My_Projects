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
        Schema::table('head_office_cases', function (Blueprint $table) {
            $table->bigInteger('last_linked_incident_id')->unsigned()->index()->nullable();// id of be_spoke_form_records
            $table->string('incident_type');
            $table->string('location_name');
            $table->unsignedInteger('location_id')->index()->nullable();
            $table->string('location_email');
            $table->string('location_phone');
            $table->string('location_full_address');
            $table->string('reported_by');
            $table->unsignedInteger('reported_by_id')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('head_office_cases', function (Blueprint $table) {
            $table->dropColumn(['last_linked_incident_id', 'incident_type', 'location_name', 'location_id', 'location_email', 'location_phone', 'location_full_address', 'reported_by', 'reported_by_id']);

        });
    }
};