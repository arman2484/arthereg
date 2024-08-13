<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
  use HasFactory;
  protected $table = 'countries_data';
  protected $fillable = [
    'country_id',
    'code_id',
    'status',
  ];
  public function country()
  {
    return $this->hasOne(CountryDetail::class, 'id', 'country_id');
  }
  public function code()
  {
    return $this->hasOne(CountryDetail::class, 'id', 'code_id');
  }
}
