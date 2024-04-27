<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Admin
 * 
 * @property int $admin_id
 * @property string $username
 * @property string $password
 * @property Carbon $created_on
 * @property Carbon $updated_on
 * @property string $status
 *
 * @package App\Models
 */
class Admin extends Model
{
	protected $table = 'admin';
	protected $primaryKey = 'admin_id';
	public $timestamps = false;

	protected $dates = [
		'created_on',
		'updated_on'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'username',
		'password',
		'created_on',
		'updated_on',
		'status'
	];
}
