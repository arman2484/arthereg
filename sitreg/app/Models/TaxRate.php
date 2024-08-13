<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
  use HasFactory;
  protected $fillable = [
    'name',
    'zone_id',
    'tax_rate',
    'type',
    'status',
  ];
  public function zoneDetail()
  {
    return $this->hasOne(Zone::class, 'id', 'zone_id');
  }
}