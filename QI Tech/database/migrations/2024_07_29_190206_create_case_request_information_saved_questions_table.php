<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseRequestInformationSavedQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_request_information_saved_questions', function (Blueprint $table) {
            $table->id(); // Creates an auto-incrementing primary key column named 'id'
            $table->string('incident_type'); // Creates a string column named 'incident_type'
            $table->text('saved_question'); // Creates a text column named 'saved_question'
            $table->timestamps(); // Creates 'created_at' and 'updated_at' timestamp columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('case_request_information_saved_questions');
    }
}
