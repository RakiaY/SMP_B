<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetBotMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sender',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
