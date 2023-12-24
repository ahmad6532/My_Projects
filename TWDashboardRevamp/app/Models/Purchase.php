<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Purchase
 * 
 * @property int $id
 * @property int $user_id
 * @property string $user_type
 * @property int|null $credits
 * @property int|null $credit_package_id
 * @property float|null $amount
 * @property string|null $txn_id
 * @property string|null $payment_status
 * @property string|null $currency_code
 * @property string|null $payment_gross
 * @property string|null $promocode_status
 * @property Carbon $created_on
 * @property Carbon $updated_on
 * @property string|null $status
 *
 * @package App\Models
 */
class Purchase extends Model
{
	protected $table = 'purchases';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'credits' => 'int',
		'credit_package_id' => 'int',
		'amount' => 'float'
	];

	protected $dates = [
		'created_on',
		'updated_on'
	];

	protected $fillable = [
		'user_id',
		'user_type',
		'credits',
		'credit_package_id',
		'amount',
		'txn_id',
		'payment_status',
		'currency_code',
		'payment_gross',
		'promocode_status',
		'created_on',
		'updated_on',
		'status'
	];
}
