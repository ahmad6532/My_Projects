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
        Schema::create('head_office_access_right_permissions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('access_rights_id')->unsigned();
            $table->bigInteger('permission_id')->unsigned();
            $table->timestamps();
            
            $table->foreign('access_rights_id')
                ->references('id')
                ->on('head_office_user_access_rights')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('permission_id')
                ->references('id')
                ->on('head_office_permissions')
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
        Schema::dropIfExists('head_office_access_right_permissions');
    }
};
