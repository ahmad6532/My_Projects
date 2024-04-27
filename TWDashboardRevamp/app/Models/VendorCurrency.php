<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class VendorCurrency
 * 
 * @property int $vendor_id
 * @property int $credits
 * @property bool $current_credit_package_status
 * @property int $current_active_credit_package_id
 * @property Carbon $bought_on
 * @property Carbon $expires_on
 *
 * @package App\Models
 */
class VendorCurrency extends Model
{
	protected $table = 'vendor_currency';
	protected $primaryKey = 'vendor_id';
	public $timestamps = false;

	protected $casts = [
		'credits' => 'int',
		'current_credit_package_status' => 'bool',
		'current_active_credit_package_id' => 'int'
	];

	protected $dates = [
		'bought_on',
		'expires_on'
	];

	protected $fillable = [
		'credits',
		'current_credit_package_status',
		'current_active_credit_package_id',
		'bought_on',
		'expires_on'
	];

	public function vendorProfileDetails_Currency(){
		return $this->belongsTo(VendorProfileDetail::class);
	}
}
