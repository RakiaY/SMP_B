<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
  protected $fillable = ['thread_id','user_id','body'];
  public function user() { return $this->belongsTo(User::class); }
}
