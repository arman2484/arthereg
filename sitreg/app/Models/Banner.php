<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
  use HasFactory;
  protected $guard = 'admin';
  protected $table = 'banners';
  protected $fillable = [
    'id',
    'store_id',
    'banner_image',
    'created_at',
    'updated_at',
  ];

  public function store()
  {
    return $this->belongsTo(VendorStore::class, 'store_id', 'id');
  }

  public function bannerImages()
  {
    return $this->hasMany(Banner::class, 'store_id', 'store_id');
  }

  public function bannerImagesData()
  {
    return $this->hasMany(Banner::class, 'store_id', 'store_id');
  }
}
