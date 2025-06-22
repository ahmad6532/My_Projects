<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PlayerVendorTransaction
 * 
 * @property int $trans_id
 * @property int $account_id
 * @property int $vendor_id
 * @property Carbon $date
 * @property int|null $creds
 * @property int|null $points
 * @property int|null $amount
 *
 * @package App\Models
 */
class PlayerVendorTransaction extends Model
{
	protected $table = 'player_vendor_transaction';
	protected $primaryKey = 'trans_id';
	public $timestamps = false;

	protected $casts = [
		'account_id' => 'int',
		'vendor_id' => 'int',
		'creds' => 'int',
		'points' => 'int',
		'amount' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'account_id',
		'vendor_id',
		'date',
		'creds',
		'points',
		'amount'
	];
	
	public function Vendortransaction(){
		return $this->belongsTo(VendorProfileDetail::class);
	}
}
