<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketChat extends Model
{
    use HasFactory;
  protected $table = 'ticket_chat';
  protected $guard = 'admin';
  protected $fillable = [
    'id',
    'ticket_id',
    'admin_message',
    'message',
    'image',
    'created_at',
    'updated_at',
  ];

  // Define the relationship with the CreateTickets model
  public function ticket()
  {
    return $this->belongsTo(CreateTicket::class, 'ticket_id');
  }
}
