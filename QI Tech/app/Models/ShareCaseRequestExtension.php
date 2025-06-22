<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareCaseRequestExtension extends Model
{
    use HasFactory;
    
    protected $dates = ['extension_time'];
    public function requested_by_user()
    {
        return $this->belongsTo(User::class,'requested_by');
    }
}
