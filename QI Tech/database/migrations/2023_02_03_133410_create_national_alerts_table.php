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
        Schema::create('national_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('status')->default('active');
            $table->string('type')->default('None');
            $table->string('custom_type')->nullable();
            # Originator is stored in sepearte column instead of comma seperated values
            $table->string('custom_originator')->nullable();
            $table->string('class');
            $table->string('action_within');
            $table->string('action_within_days')->nullable();
            $table->text('summary');
            $table->string('send_to_head_offices_or_location')->default('all');

            $table->boolean('send_to_all_head_offices')->default(0);
            $table->boolean('send_to_all_locations')->default(0);

            $table->boolean('patient_level_recall')->default(0);
            # By CAS (master admin) or HO 
            $table->string('created_by')->default('CAS');
            $table->bigInteger('admin_id');

            #Headoffice related
            $table->bigInteger('head_office_id')->nullable();
            $table->boolean('is_archived')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('national_alerts');
    }
};
