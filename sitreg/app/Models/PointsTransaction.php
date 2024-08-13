<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointsTransaction extends Model
{
  protected $guard = 'admin';
  protected $table = 'points_transaction';

  protected $fillable = [
    'id',
    'user_id',
    'amount',
    'created_at',
    'updated_at',
  ];
}
