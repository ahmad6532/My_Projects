<?php

namespace App\Models;

use App\Traits\BelongsToLocation;
use App\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LocationUser extends Model
{
    use HasFactory,BelongsToLocation,BelongsToUser;

    protected static function boot(){
        parent::boot();

        static::created(function ($locationUser) {
            $user = $locationUser->user;
            $contact = new new_contacts();
            $contact->name = $user->first_name . ' ' . $user->surname;
            $contact->head_office_id = $locationUser->location->head_office_location->head_office_id;
            $contact->registration_no = $user->registration_no;
            $contact->personal_emails = json_encode([$user->email]);
            $contact->personal_mobiles = json_encode([$user->mobile_no]);
            $contact->date_of_birth = $user->dob ?? null;
            $contact->save();
        });
    }
    

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class,'location_id');
    }
}
