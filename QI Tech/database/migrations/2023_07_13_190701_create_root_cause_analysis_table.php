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
        Schema::create('root_cause_analysis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id')->index();
            $table->string('type');
            $table->string('name');
            $table->tinyInteger('is_editable')->default(0);
            $table->tinyInteger('status')->nullable();
            $table->text('note')->nullable();
            $table->unsignedInteger('completed_by')->index()->nullable();
            $table->timestamps();

            //$table->unique(['type','case_id'],'case_id_type');

            $table->foreign('case_id')
            ->on('head_office_cases')
            ->references('id')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('completed_by')
            ->on('users')
            ->references('id')
            ->onDelete('set null')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('root_cause_analysis');
    }
};
