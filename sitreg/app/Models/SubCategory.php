<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
  use HasFactory;
  protected $guard = 'admin';
  protected $table = 'sub_categories';
  protected $fillable = [
    'id',
    'category_id',
    'sub_category_name',
    'sub_category_status',
    'created_at',
    'updated_at',
  ];

  // Define the relationship with Product
  public function products()
  {
    return $this->hasMany(Product::class, 'sub_category_id');
  }

  public function category()
  {
    return $this->belongsTo(Category::class);
  }
}
