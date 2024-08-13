<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryDetail extends Model
{
  use HasFactory;
  protected $table = 'countries';
  protected $fillable = [
    'name',
    'iso3',
    'phonecode',
    'capital',
    'currency',
    'currency_name',
    'currency_symbol',
    'region',
    'region_id',
    'timezones',
  ];
}
