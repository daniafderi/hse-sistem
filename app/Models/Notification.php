<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table= "notifications";
    protected $fillable = ['type','title','message','notifiable_id','notifiable_type','created_by'];

    public function users()
{
    return $this->belongsToMany(User::class, 'notification_users')
        ->withPivot('is_read', 'read_at')
        ->withTimestamps();
}

public function notifiable()
{
    return $this->morphTo();
}
}
