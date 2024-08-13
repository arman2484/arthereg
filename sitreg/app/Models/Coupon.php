<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
  use HasFactory;
  protected $guard = 'admin';
  protected $fillable = [
    'coupon_code',
    'discount_amount',
    'status',
    'description',
  ];
}
