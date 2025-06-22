<?php

namespace App\Traits;

use App\Models\databases\Database;
use Illuminate\Http\Request;
trait BelongsToDatabase{

    public function admin()
    {
        return $this->belongsTo(Database::class,'database_id');
    }
}