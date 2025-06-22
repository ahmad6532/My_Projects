<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $casts = ['date_of_birth'];
    // public function contact_patient()
    // {
    //     return $this->hasOne(PatientContact::class,'contact_id');
    // }
    // public function contact_prescriber()
    // {
    //     return $this->hasOne(PrescriberContact::class,'contact_id');
    // }
    public function contact_address()
    {
        return $this->hasMany(ContactAddress::class,'contact_id');
    }

    public function contact_cases()
    {
        return $this->hasMany(CaseContact::class, 'contact_id');
    }

    // public function setDateOfBirthAttribute($value)
    // {
    //     try{
    //         $this->date_of_birth = Carbon::createFromFormat('Y-m-d', $value);
    //     }
    //     catch(Exception $e){
            
    //     }
    // }

    public function getCaseCountAttribute()
    {
        
        return count($this->contact_cases);
    }
   public function contact_connections()
   {
        return $this->hasMany(ContactConnection::class,'contact_id');
   }

   public function getNameAttribute()
   {
        return $this->first_name.' '.$this->last_name;
   }
   public function getDateOfBirthAttribute($value)
   {
    return Carbon::parse($value);
   }
   public function getSingleAddressAttribute()
   {
        if(count($this->contact_address) > 0)
            return $this->contact_address->last();
   }
   public function getEmailsAttribute()
   {
        if($this->email_address)
            return json_decode($this->email_address);
        return [];
   }
   public function getTelephonesAttribute()
   {
        if($this->telephone_no)
            return json_decode($this->telephone_no);
        return [];
   }
}
