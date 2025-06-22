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
        try{
            Schema::create('new_contacts', function (Blueprint $table) {
                $table->id();
                $table->text("avatar")->nullable();
                $table->timestamp("date_of_birth")->nullable();
                $table->text('nhs_no')->nullable();
                $table->text('ethnicity')->nullable();
                $table->text('marital_status')->nullable();
                $table->text('gender')->nullable();
                $table->text('pronoun')->nullable();
                $table->text('religion')->nullable();
                $table->text('passport_no')->nullable();
                $table->text('driver_license_no')->nullable();
                $table->text('profession')->nullable();
                $table->text('registration_no')->nullable();
                $table->text('other')->nullable();
                $table->json("work_emails")->nullable();
                $table->json("personal_emails")->nullable();
                $table->json("work_mobiles")->nullable();
                $table->json("personal_mobiles")->nullable();
                $table->json("home_telephones")->nullable();
                $table->json("work_telephones")->nullable();
                $table->text("facebook")->nullable();
                $table->text("instagram")->nullable();
                $table->text("twitter")->nullable();
                $table->text("other_link")->nullable();
                $table->unsignedInteger('head_office_id');
                $table->foreign('head_office_id')->references('id')->on('head_offices')->onDelete('cascade');
                $table->text("name");
                $table->timestamps();
            });
        }catch(Exception $e){
            $this->down();
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_contacts');
    }
};
