<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreImages extends Model
{
  use HasFactory;
  protected $table = 'store_images';
  protected $guard = 'admin';
  protected $fillable = [
    'id',
    'store_id',
    'store_images',
    'created_at',
    'updated_at',
  ];
}
