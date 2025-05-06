<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Pet
 * 
 * @property int $id
 * @property int $pet_owner_id
 * @property string $type
 * @property string $breed
 * @property string $name
 * @property string $gender
 * @property Carbon $birth_date
 * @property string|null $color
 * @property float|null $weight
 * @property string|null $description
 * @property bool $is_vaccinated
 * @property bool $has_contagious_diseases
 * @property bool $has_medical_file
 * @property bool $is_critical_condition
 * 
 * @property User $user
 * @property Collection|PetMedia[] $pet_media
 *
 * @package App\Models
 */
class Pet extends Model
{
	protected $table = 'pets';
	public $timestamps = false;

	protected $casts = [
		'pet_owner_id' => 'int',
		'birth_date' => 'datetime',
		'weight' => 'float',
		'is_vaccinated' => 'bool',
		'has_contagious_diseases' => 'bool',
		'has_medical_file' => 'bool',
		'is_critical_condition' => 'bool'
	];

	protected $fillable = [
		'pet_owner_id',
		'type',
		'breed',
		'name',
		'gender',
		'birth_date',
		'color',
		'weight',
		'description',
		'is_vaccinated',
		'has_contagious_diseases',
		'has_medical_file',
		'is_critical_condition'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'pet_owner_id');
	}

	public function PetMedia()
	{
		return $this->hasMany(PetMedia::class);
	}
}
