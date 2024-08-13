<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
  use HasFactory;
  protected $fillable = [
    'title',
    'message',
    'user_id',
    'sender_id',
    'type',
    'is_seen',
  ];
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}