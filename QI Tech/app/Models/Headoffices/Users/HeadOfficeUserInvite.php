<?php

namespace App\Models\Headoffices\Users;

use App\Models\head_office_access_rights;
use App\Models\HeadOffice;
use App\Models\HeadOfficeUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadOfficeUserInvite extends Model
{
    protected $table = "head_office_invites";
    use HasFactory;
    protected $fillable = [
        'email',
        'head_office_position',
        'head_office_user_profile_id',
        'invited_by_id',
        'invited_by_type',
        'expires_at',
        'token',
        'head_office_id'
    ];

/**
* The attributes that should be mutated to dates.
*
* @var array
*/
protected $dates = ['expires_at'];
public function head_office_profile()
{
    return $this->belongsTo(head_office_access_rights::class,'head_office_user_profile_id');
}

public function headOfficeUser(){
    return $this->belongsTo(HeadOfficeUser::class,'invited_by_id');
}
}
