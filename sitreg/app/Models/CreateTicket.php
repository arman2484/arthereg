<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreateTicket extends Model
{
  use HasFactory;
  protected $fillable = [
    'ticket_id',
    'user_id',
    'order_id',
    'message',
    'subject',
    'image',
  ];

  // Define the relationship with the TicketChat model
  public function chats()
  {
    return $this->hasMany(TicketChat::class, 'ticket_id');
  }

  // Define the relationship with the User model
  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }
}
