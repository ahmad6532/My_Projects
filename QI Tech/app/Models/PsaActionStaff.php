<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class PsaActionStaff extends Model
{
    use HasFactory;
    protected $table = 'psa_action_staff';

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
