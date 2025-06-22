<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultDocument extends Model
{
    use HasFactory;

    public function documents(){
        return $this->hasMany(DefaultDocumentDocument::class,'default_document_id');
    }
    public function uploadedByUser(){
        return $this->belongsTo(User::class,'uploaded_by');
    }
    public function updatedByUser(){
        return $this->belongsTo(User::class,'updated_by');
    }
}
