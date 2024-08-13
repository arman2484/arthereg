<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLike extends Model
{
  use HasFactory;
  protected $guard = 'admin';
  protected $table = 'product_likes';
  protected $fillable = [
    'id',
    'user_id',
    'product_id',
    'created_at',
    'updated_at'
  ];
}
