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
        Schema::create('head_office_cases', function (Blueprint $table) {
            $table->id();
            // $table->bigInteger('case_number')->unique();
            $table->integer('head_office_id')->unsigned();
            $table->string('status')->nullable();
            $table->text('description');
            //$table->bigInteger('manager');
            $table->boolean('case_closed')->default(0);
            $table->timestamp('last_accessed')->nullable()->useCurrent();
            $table->timestamp('last_action')->nullable()->useCurrent();
            $table->timestamps();

            $table->foreign('head_office_id')
                ->references('id')
                ->on('head_offices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('head_office_cases');
    }
};
