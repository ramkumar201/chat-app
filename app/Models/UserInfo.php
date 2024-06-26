<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;

    protected $table = 'userinfo';

    protected $fillable = [
        'user_id',
        'bio',
        'address',
        'city',
        'state',
        'country',
        'phoneno'
    ];
}
