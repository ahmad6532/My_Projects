<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class VendorProfileDetail
 *
 * @property int $vendor_id
 * @property string $vendor_promocode
 * @property string $user_type
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string|null $password
 * @property string $phone_number
 * @property int|null $credits
 * @property string $address
 * @property Carbon|null $created_on
 * @property Carbon|null $updated_on
 * @property string $status
 * @property bool|null $is_verified
 * @property string $is_drawer_start
 *
 * @package App\Models
 */
class VendorProfileDetail extends Model
{
	protected $table = 'vendor_profile_details';
	protected $primaryKey = 'vendor_id';
	public $timestamps = false;

	protected $casts = [
		'credits' => 'int',

	];

	protected $dates = [
		'created_on',
		'updated_on'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'vendor_promocode',
		'user_type',
		'first_name',
		'last_name',
		'email',
		'password',
		'phone_number',
		'credits',
		'address',
		'created_on',
		'updated_on',
		'status',
		'is_verified',
		'is_drawer_start'
	];
	public function vendorDrawer(){
		return $this->hasMany(VendorDrawer::class);
	}
	public function vendorCurrency(){
		return $this->hasMany(VendorCurrency::class);
	}
	public function vendorAccountDetails(){
		return $this->hasMany(AccountDetail::class);
	}
	public function playerVendorTransaction(){
		return $this->hasMany(PlayerVendorTransaction::class);
	}
}
