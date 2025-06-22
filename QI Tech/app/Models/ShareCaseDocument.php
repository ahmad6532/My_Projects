<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareCaseDocument extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function document(){
        return $this->belongsTo(Document::class,'document_id');
    }
}
