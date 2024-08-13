<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{
  use HasFactory;
  protected $guard = 'admin';
  protected $table = 'product_images';
  protected $fillable = [
    'id',
    'product_id',
    'product_image',
    'created_at',
    'updated_at',
  ];
  public function product()
  {
    return $this->belongsTo(Product::class);
  }
}
