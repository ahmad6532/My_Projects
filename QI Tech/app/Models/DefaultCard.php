<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultCard extends Model
{
    use HasFactory;
    public function fields()
    {
        return $this->hasMany(DefaultCardField::class,'default_card_id');
    }
}
