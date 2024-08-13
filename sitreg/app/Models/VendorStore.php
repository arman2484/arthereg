<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorStore extends Model
{
  use HasFactory;
  protected $table = 'vendor_stores';
  protected $guard = 'admin';
  protected $fillable = [
    'id',
    'vendor_id',
    'banner_id',
    'module_id',
    'store_name',
    'store_description',
    'business_email',
    'store_image',
    'vat_gstin_no',
    'country_code',
    'mobile',
    'lat',
    'lon',
    'store_address',
    'store_logo',
    'delivery_time',
    'vendor_request',
    'status',
    'created_at',
    'updated_at',
    'open_time',
    'close_time',
    'mfo',
    'delivery',
    'take_away',
  ];
  public function storeImages()
  {
    return $this->hasMany(StoreImages::class, 'store_id', 'id');
  }

  public function bannerImages()
  {
    return $this->hasMany(Banner::class, 'store_id', 'id');
  }
  public function vendorDetail()
  {
    return $this->hasOne(Vendor::class, 'id', 'vendor_id');
  }

  // Define the relationship back to Vendor
  public function vendor()
  {
    return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
  }
}
