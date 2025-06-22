<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class deadlineCaseTask extends Model
{
    use HasFactory;

    public function deadline(){
        return $this->belongsTo(task_deadline_records::class);
    }
}
