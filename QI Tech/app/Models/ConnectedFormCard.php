<?php

namespace App\Models;

use App\Models\Forms\FormCard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectedFormCard extends Model
{
    use HasFactory; 
    public function from_card(){
        return $this->belongsTo(FormCard::class, 'form_card_id');
    }
}
