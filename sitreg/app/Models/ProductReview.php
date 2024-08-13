<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
  use HasFactory;
  protected $guard = 'admin';
  protected $fillable = [
    'user_id',
    'product_id',
    'review_star',
    'review_message',
  ];
  public function productImages()
  {
    return $this->hasMany(ProductImages::class, 'product_id', 'product_id');
  }

  public function product()
  {
    return $this->belongsTo(Product::class, 'product_id');
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }
}
