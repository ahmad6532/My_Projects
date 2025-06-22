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
        Schema::create('near_miss_managers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('head_office_id');
            $table->foreign('head_office_id')->references('id')->on('head_offices');
            $table->unsignedBigInteger('be_spoke_form_category_id')->nullable();
            $table->foreign('be_spoke_form_category_id')
            ->references('id')
            ->on('be_spoke_form_categories')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->boolean('isActive')->default(false);
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
        Schema::dropIfExists('near_miss_managers');
    }
};
