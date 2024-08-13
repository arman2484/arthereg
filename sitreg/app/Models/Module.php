<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
  protected $table = 'module';
  protected $guard = 'admin';
  protected $fillable = [
    'id',
    'name',
    'image',
    'created_at',
    'updated_at',
  ];
}
