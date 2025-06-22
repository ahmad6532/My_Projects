<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
   

    protected $table ='languages';
    protected $fillable =
    [
        'language_name',
        'is_active',
        'is_deleted',
       
    ];
    public function user_language(){
        return $this->hasmany(user_language::class);
    }
}
