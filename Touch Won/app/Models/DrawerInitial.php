<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DrawerInitial
 *
 * @property int $id
 * @property int $drawer_id
 * @property float $initial_amount
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class DrawerInitial extends Model
{
	protected $table = 'drawer_initial';


	protected $casts = [
		'drawer_id' => 'int',
		'initial_amount' => 'float'
	];
    protected $dates = [
        'match_date'
    ];
	protected $fillable = [
		'drawer_id',
		'initial_amount',
        'match_date'
	];
}
