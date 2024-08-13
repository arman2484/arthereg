<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Units extends Model
{
  use HasFactory;
  protected $table = 'unit';
  protected $guard = 'admin';
  protected $fillable = [
    'id',
    'name',
    'created_at',
    'updated_at',
  ];
}
