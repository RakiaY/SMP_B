<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Message;

class Thread extends Model
{
    protected $fillable = ['postulation_id'];
  public function participants() {
    return $this->belongsToMany(User::class, 'thread_user');
  }
  public function messages() {
    return $this->hasMany(Message::class)->latest();
  }
}