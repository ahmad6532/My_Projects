<?php

namespace App\Models;

use App\Models\Forms\StageQuestion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GdprFormField extends Model
{
    use HasFactory;
    public function question() {
        return $this->belongsTo(StageQuestion::class,'be_spoke_form_question_id');
    }
    public function gdpr_tag() {
        return $this->belongsTo(GdprTag::class,'gdpr_tag_id');
    }
}
