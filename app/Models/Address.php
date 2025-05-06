<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Address
 * 
 * @property int $id
 * @property int $user_id
 * @property string|null $address_type
 * @property string|null $street
 * @property string|null $city
 * @property string|null $zipcode
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Address extends Model
{
	protected $table = 'addresses';

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'address_type',
		'street',
		'city',
		'zipcode'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
