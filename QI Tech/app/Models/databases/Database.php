<?php

namespace App\Models\databases;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Database extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'recordable_type',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class,'admin_id');
    }


    protected function records()
    {
        return $this->morphMany($this->recordable_type,'database_id');
    }
}
