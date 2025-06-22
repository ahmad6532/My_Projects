<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user_language extends Model
{
     protected $table ='emp_languages';
    protected $fillable =
    [
        'emp_id',
        'language_id',
        'is_active',
        'is_deleted',
    ];
    public function language(){
        return $this->belongsTo(Language::class, 'language_id','id');
    }
}
