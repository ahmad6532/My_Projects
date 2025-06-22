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
        Schema::create('head_office_access_rights', function (Blueprint $table) {
            $table->id();
            $table->integer('head_office_id')->unsigned();
            $table->foreign('head_office_id')
                ->references('id')
                ->on('head_offices')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->bigInteger('head_office_user_id')->unsigned()->nullable();
            $table->foreign('head_office_user_id')
                ->references('id')
                ->on('head_office_users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('profile_name');
            $table->boolean('system_default_profile')->nullable()->default(0);
            $table->boolean('super_access')->nullable()->default(0);
            $table->boolean('is_manage_forms')->default(false);
            $table->boolean('is_manage_company_account')->default(false);
            $table->boolean('is_manage_team')->default(false);
            $table->boolean('is_manage_location_users')->default(false);
            $table->boolean('is_manage_alert_settings')->default(false);
            $table->boolean('is_access_company_activity_log')->default(false);
            $table->boolean('is_access_contacts')->default(false);
            $table->integer('custom_access_rights_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('head_office_access_rights');
    }
};
