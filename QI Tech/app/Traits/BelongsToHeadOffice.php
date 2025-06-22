<?php

namespace App\Traits;

use App\Models\HeadOffice;
use Illuminate\Http\Request;
trait BelongsToHeadOffice{

    public function head_office()
    {
        return $this->belongsTo(HeadOffice::class,'head_office_id');
    }
}