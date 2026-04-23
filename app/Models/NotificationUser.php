<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationUser extends Model
{
    protected $fillable =
    ['notification_id', 'user_id', 'is_read', 'read_at'];
}
