<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Make sure this line is present
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;


class Vendor extends Authenticatable
{
  use HasApiTokens, HasFactory, HasProfilePhoto, HasTeams, Notifiable, TwoFactorAuthenticatable;

  protected $table = 'vendors';
  protected $guard = 'admin';
  protected $fillable = [
    'id',
    'first_name',
    'last_name',
    'email',
    'email_verified_at',
    'username',
    'password',
    'mobile',
    'country_code',
    'image',
    'status',
    'login_type',
    'device_token',
    'otp',
    'created_at',
    'updated_at',
  ];


  // Define the relationship with VendorStore
  public function vendorStore()
  {
    return $this->hasOne(VendorStore::class, 'vendor_id', 'id');
  }
}
