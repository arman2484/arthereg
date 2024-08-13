<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulebanner extends Model
{
    use HasFactory;
  protected $table = 'module_banner';
  protected $guard = 'admin';
  protected $fillable = [
    'id',
    'module_id',
    'image',
    'created_at',
    'updated_at',
  ];
}
