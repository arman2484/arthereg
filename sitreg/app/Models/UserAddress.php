<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
  use HasFactory;
  protected $guard = 'admin';
  protected $table = 'user_address';
  protected $fillable = [
    'id',
    'user_id',
    'first_name',
    'last_name',
    'mobile',
    'pincode',
    'address',
    'locality',
    'city',
    'state',
    'type',
    'default_address',
    'last_address_status',
    'address_flag',
    'country_code',
    'admin',
  ];
}
