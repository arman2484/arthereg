<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  use HasFactory;
  protected $table = 'categories';
  protected $guard = 'admin';
  protected $fillable = [
    'id',
    'category_name',
    'category_image',
    'category_icon',
    'status',
    'created_at',
    'updated_at',
  ];


  public function sub_categories()
  {
    return $this->hasMany(SubCategory::class);
  }

}
