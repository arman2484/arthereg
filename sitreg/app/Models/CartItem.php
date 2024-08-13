<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
  use HasFactory;
  protected $guard = 'admin';
  protected $fillable = [
    'user_id',
    'product_id',
    'product_color',
    'product_size',
    'quantity',
    'coupon_id',
    'address_id',
    'product_price',
    'admin_cart',
    'variant_id',
  ];
  public function productImages()
  {
    return $this->hasMany(ProductImages::class, 'product_id', 'product_id')->select('product_images.product_id', 'product_images.product_image');
  }

  public function product()
  {
    return $this->belongsTo(Product::class, 'product_id', 'id');
  }
}
