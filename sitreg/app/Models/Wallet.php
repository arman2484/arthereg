<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
  use HasFactory;
  protected $guard = 'admin';
  protected $table = 'wallet';

  protected $fillable = [
    'id',
    'user_id',
    'payment_method',
    'amount',
    'status',
    'success',
    'created_at',
    'updated_at',
  ];
}
