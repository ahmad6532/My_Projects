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
        // for linking a location incident to Head office's case
        Schema::create('head_office_linked_cases', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('head_office_case_id')->unsigned()->unique()->index(); // 1-1 will cover it up

            $table->bigInteger('be_spoke_form_record_id')->unsigned()->unique()->index(); // This is an actual incident or case !
            //1 case can be added to multiple head offices' case manager. this is done when it will be shared.
            //1 case will always be a property of 1 head office's case manager. sharing will be done on a sharing table basis.
            // setting it unique now !

            $table->timestamps();

            $table->foreign('head_office_case_id')
                ->references('id')
                ->on('head_office_cases')->onDelete('cascade');

            $table->foreign('be_spoke_form_record_id')
                ->references('id')
                ->on('be_spoke_form_records')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('head_office_linked_cases');
    }
};
