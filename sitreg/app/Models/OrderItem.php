<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
  use HasFactory;
  protected $guard = 'admin';
  protected $table = 'order_items';
  protected $fillable = [
    'user_id',
    'order_id',
    'product_id',
    'coupon_id',
    'vendor_id',
    'payment_id',
    'address_id',
    'product_price',
    'payment_mode',
    'product_color',
    'product_size',
    'quantity',
    'delivery_date',
    'is_status',
    'price',
    'admin',
    'variant_id',
  ];
  public function productImages()
  {
    return $this->hasMany(ProductImages::class, 'product_id', 'product_id');
  }

  // Define the product relationship
  public function product()
  {
    return $this->belongsTo(Product::class, 'product_id', 'id');
  }

  public function variant()
  {
    return $this->belongsTo(Variant::class, 'variant_id');
  }
}
