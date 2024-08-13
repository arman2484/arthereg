<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  use HasFactory;
  protected $guard = 'admin';
  protected $fillable = [
    'user_id',
    'order_id',
    'total_item',
    'address_id',
    'coupon_id',
    'payment_mode',
    'order_status',
    'total_amount',
    'order_status',
    'admin',
  ];

  public function items()
  {
    return $this->hasMany(OrderItem::class);
  }
}
