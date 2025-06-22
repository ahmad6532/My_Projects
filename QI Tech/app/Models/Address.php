<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    public function contact_addresses()
    {
        return $this->hasMany(ContactAddress::class,'address_id');
    }
    public function getCurrentAddressesAttribute()
    {
        return $this->contact_addresses->where('is_present_address',1);
    }
    
    public function getPastAddressesAttribute()
    {
        return $this->contact_addresses->where('is_present_address',0);

    }
}
