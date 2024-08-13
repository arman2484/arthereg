<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
  use HasFactory;
  protected $fillable = [
    'name',
    'country_id',
    'status',
  ];

  public function zoneDetail()
  {
    return $this->hasMany(ZoneDetail::class, 'zone_id', 'id');
  }
  public function stateDetail()
  {
    return $this->belongsToMany(State::class, 'state_id', 'id');
  }
  public function country()
  {
    return $this->hasOne(CountryDetail::class, 'id', 'country_id');
  }
  public function code()
  {
    return $this->hasOne(CountryDetail::class, 'id', 'code_id');
  }
}
