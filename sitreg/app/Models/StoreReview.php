<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreReview extends Model
{
  use HasFactory;
  protected $table = 'store_reviews';
  protected $guard = 'admin';
  protected $fillable = [
    'id',
    'user_id',
    'store_id',
    'review_star',
    'review_message',
  ];
  public function storeImages()
  {
    return $this->hasMany(StoreImages::class, 'id', 'store_id');
  }
}
