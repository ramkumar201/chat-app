<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoomMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'chat_room_id',
        'message',
    ];
}
