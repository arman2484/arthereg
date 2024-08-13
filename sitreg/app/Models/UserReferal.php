<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReferal extends Model
{
  use HasFactory;
  protected $fillable = [
    'user_id',
    'refer_code',
    'user_refer_code',
    'use_refer_code_by',
  ];
  public function userReferalCode()
  {
    return $this->hasMany(User::class, 'id', 'user_id');
  }
  public function userReferal()
  {
    return $this->hasOne(User::class, 'id', 'use_refer_code_by');
  }
}
