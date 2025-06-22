<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultDocumentDocument extends Model
{
    use HasFactory;

    public function document(){
        return $this->belongsTo(Document::class,'document_id');
    }
}
