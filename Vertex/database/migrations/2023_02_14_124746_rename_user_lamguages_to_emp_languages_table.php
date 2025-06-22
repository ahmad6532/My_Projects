<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameUserLamguagesToEmpLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
            Schema::rename('user_languages', 'emp_languages');
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       
            Schema::rename('emp_languages', 'user_languages');
      
    }
}
