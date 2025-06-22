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
        DB::statement("ALTER TABLE near_miss_managers CHANGE COLUMN allow_editing_state allow_editing_state ENUM('disable', 'minutes', 'hour', 'day', 'week', 'always') DEFAULT 'always'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
{
    DB::statement("ALTER TABLE near_miss_managers CHANGE COLUMN allow_editing_state allow_editing_state ENUM('disable', 'hour', 'day', 'week', 'always') DEFAULT 'always'");
}
};
