<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
  use HasFactory;
  protected $table = 'variants';
  protected $guard = 'admin';
  protected $fillable = [
    'id',
    'product_id',
    'color',
    'size',
    'type',
    'price',
    'total_stock',
    'status',
    'created_at',
    'updated_at',
  ];

  // Define the relationship to Product
  // public function product()
  // {
  //   return $this->belongsTo(Product::class);
  // }

}
