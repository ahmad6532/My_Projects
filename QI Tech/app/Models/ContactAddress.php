<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactAddress extends Model
{
    use HasFactory;
    public function address()
    {
        return $this->belongsTo(Address::class,'address_id');
    }
    public function contact()
    {
        return $this->belongsTo(Contact::class,'contact_id');
    }
}
