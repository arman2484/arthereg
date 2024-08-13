<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
  use HasFactory;
  protected $guard = 'admin';
  protected $fillable = [
    'brand_name',
  ];
}
