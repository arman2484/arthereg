<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
  use HasFactory;
  protected $fillable = [
    'privacy_policy',
    'term_policy',
    'app_logo',
    'app_name',
    'app_color',
    'stripe_key',
    'stripe_secret',
    'page_url'
  ];
}
