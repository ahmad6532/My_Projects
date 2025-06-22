<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class VendorCreditPackage
 * 
 * @property int $credit_package_id
 * @property string|null $package_name
 * @property int $package_expiry_date
 * @property int $amount
 * @property int $credits_value_count
 * @property Carbon $created_on
 * @property Carbon $updated_on
 * @property bool $is_enabled
 *
 * @package App\Models
 */
class VendorCreditPackage extends Model
{
	protected $table = 'vendor_credit_packages';
	protected $primaryKey = 'credit_package_id';
	public $timestamps = false;

	protected $casts = [
		'package_expiry_date' => 'int',
		'amount' => 'int',
		'credits_value_count' => 'int',
		'is_enabled' => 'bool'
	];

	protected $dates = [
		'created_on',
		'updated_on'
	];

	protected $fillable = [
		'package_name',
		'package_expiry_date',
		'amount',
		'credits_value_count',
		'created_on',
		'updated_on',
		'is_enabled'
	];
}
