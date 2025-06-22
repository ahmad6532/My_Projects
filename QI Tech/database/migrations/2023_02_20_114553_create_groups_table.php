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
        Schema::create('head_office_organisation_groups', function (Blueprint $table) {
            $table->id();
            $table->integer('head_office_id')->unsigned();
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->string('group');
            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id')
                ->on('head_office_organisation_groups');

            $table->foreign('head_office_id')
                ->references('id')
                ->on('head_offices')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('head_office_organisation_groups');
    }
};
