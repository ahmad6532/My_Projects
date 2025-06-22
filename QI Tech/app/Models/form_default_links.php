<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class form_default_links extends Model
{
    use HasFactory;
    public function uploadedByUser(){
        return $this->hasOneThrough(User::class, HeadOfficeUser::class, 'id', 'id', 'uploaded_by', 'user_id');
    }
    public function updatedByUser(){
        return $this->hasOneThrough(User::class, HeadOfficeUser::class, 'id', 'id', 'updated_by', 'user_id');
    }

    public function headOfficeUser()
    {
        return $this->belongsTo(HeadOfficeUser::class, 'uploaded_by');
    }
    public function headOfficeUserUpdated()
    {
        return $this->belongsTo(HeadOfficeUser::class, 'updated_by');
    }

}
