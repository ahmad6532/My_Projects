<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseRequestInformationSavedQuestion extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'case_request_information_saved_questions';

    // The attributes that are mass assignable.
    protected $fillable = [
        'incident_type',
        'saved_question',
    ];
}
