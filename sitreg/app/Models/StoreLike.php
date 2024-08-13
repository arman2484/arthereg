<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreLike extends Model
{
  use HasFactory;
  protected $guard = 'admin';
  protected $table = 'store_likes';
  protected $fillable = [
    'id',
    'user_id',
    'store_id',
    'created_at',
    'updated_at'
  ];
}
