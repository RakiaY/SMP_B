<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PetMedia
 * 
 * @property int $id
 * @property int $pet_id
 * @property string $media_patth
 * @property string $media_type
 * @property bool $is_thumbnail
 * @property Carbon|null $uploaded_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Pet $pet
 *
 * @package App\Models
 */
class PetMedia extends Model
{
	protected $table = 'pet_medias';

	protected $casts = [
		'pet_id' => 'int',
		'is_thumbnail' => 'bool',
		'uploaded_at' => 'datetime'
	];

	protected $fillable = [
		'pet_id',
		'media_patth',
		'media_type',
		'is_thumbnail',
		'uploaded_at'
	];

	public function pet()
	{
		return $this->belongsTo(Pet::class);
	}
}
