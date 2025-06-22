<?php

use App\Models\Location;
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
        Schema::table('locations', function (Blueprint $table) {
            $table->string('username')->nullable(false)->after('trading_name');
            //
        });
        // This is to save existing data like locations!
        Schema::table('locations', function (Blueprint $table) {
            $locations = Location::all();
            for($i=0;$i<count($locations);$i++)
            {
                $locations[$i]->username = $i;
                $locations[$i]->save();
            }
            $table->unique('username');
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            if (Schema::hasColumn('locations','username')){
                $table->dropColumn('username');
            }
            //
        });
    }
};
