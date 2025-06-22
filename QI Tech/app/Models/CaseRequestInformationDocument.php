<?php

namespace App\Models;

use App\Models\Headoffices\CaseManager\CommentDocument;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseRequestInformationDocument extends Model
{
    use HasFactory;
    public function document()
    {
        return $this->belongsTo(CommentDocument::class,'comment_document_id');
    }
}
