<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Payment
 * 
 * @property int $payment_id
 * @property string $item_number
 * @property string $txn_id
 * @property string $payment_gross
 * @property string $currency_code
 * @property string $payment_status
 *
 * @package App\Models
 */
class Payment extends Model
{
	protected $table = 'payments';
	protected $primaryKey = 'payment_id';
	public $timestamps = false;

	protected $fillable = [
		'item_number',
		'txn_id',
		'payment_gross',
		'currency_code',
		'payment_status'
	];
}
