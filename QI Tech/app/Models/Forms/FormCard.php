<?php

namespace App\Models\Forms;

use App\Models\ConnectedFormCard;
use App\Models\DefaultCard;
use App\Models\LinkedFormCard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormCard extends Model
{
    use HasFactory;
    public function default_card()
    {
        return $this->belongsTo(DefaultCard::class,'default_card_id');
    }

    public function question() // link with answer
    {
        return $this->hasMany(StageQuestion::class, 'form_card_id'); // update it later !
    }
    public function connected_form_card()
    {
        return $this->hasOne(ConnectedFormCard::class,'form_card_id');
    }

    public function group()
    {
        $gid = 0;
        if($this->connected_form_card)
            $gid = $this->connected_form_card->group_id;
        return ConnectedFormCard::where('group_id', $gid)->get();
            
    }

    public function getConnectedFormCardIdsAttribute()
    {
        return $this->group()->pluck('form_card_id')->toArray();
    }
    public function group_del()
    {
        $gid = 0;
        if($this->connected_form_card)
            $gid = $this->connected_form_card->group_id;
        return ConnectedFormCard::where('group_id', $gid);
            
    }
}
