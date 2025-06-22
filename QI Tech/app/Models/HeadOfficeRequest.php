<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeadOfficeRequest extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'head_office_requests';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'first_name',
                  'surname',
                  'organization',
                  'position',
                  'email',
                  'telephone_no',
                  'email_verification_key',
                  'request_type',
                  'address',
              ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
               'email_verified_at'
           ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
    

    /**
     * Set the email_verified_at.
     *
     * @param  string  $value
     * @return void
     */
//    public function setEmailVerifiedAtAttribute($value)
//    {
//        $this->attributes['email_verified_at'] = !empty($value) ? \DateTime::createFromFormat('[% date_format %]', $value) : null;
//    }

public function getNameAttribute()
{
    return $this->first_name .' '.$this->surname;
}

public function getStatusAttribute(){
    $status[0]='Pending';
    $status[1]='warning';

    if($this->request_type==1)
    {
        $status[0]='Approved';
        $status[1]='success';
    }
    else if($this->request_type==2)
    {
        $status[0]='Rejected';
        $status[1]='orange';
    }

    return $status;

}


}
