<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class ContactUs extends Model

{

  use HasFactory;

  protected $fillable = [

    'user_id',

    'first_name',

    'last_name',

    'email',

    'last_name',

    'mobile',

    'message',

    'from_user',

    'order_id',

    'from_user',

    'to_user',

    'status',
    'ticket_id',
    'image',

  ];
}
