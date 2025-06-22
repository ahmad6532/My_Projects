<?php

namespace App\Models\Forms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Forms\StageQuestion;
class QuestionGroup extends Model
{
    use HasFactory;

    protected $table= "be_spoke_form_question_groups";

    public function questions(){
        return $this->hasMany(StageQuestion::class,'group_id');
    }
}
