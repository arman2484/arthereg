<?php

namespace App\Models;

use App\Models\ProductImages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  use HasFactory;
  protected $table = 'products';
  protected $guard = 'admin';
  protected $fillable = [
    'id',
    'vendor_id',
    'store_id',
    'category_id',
    'sub_category_id',
    'product_name',
    'product_image',
    'product_price',
    'product_review',
    'product_size',
    'product_color',
    'product_sale_price',
    'product_about',
    'product_quantity',
    'status',
    'product_sku',
    'key_features',
    'in_stock',
    'created_at',
    'updated_at',
  ];

  public function productImages()
  {
    return $this->hasMany(ProductImages::class, 'product_id', 'id');
  }

  // Define the inverse relationship with SubCategory
  public function subCategory()
  {
    return $this->belongsTo(SubCategory::class, 'sub_category_id');
  }

  // Define the relationship with the Category
  public function category()
  {
    return $this->belongsTo(Category::class, 'category_id');
  }

  public function store()
  {
    return $this->belongsTo(VendorStore::class, 'store_id');
  }

  public function variants()
  {
    return $this->hasMany(Variant::class);
  }
}
