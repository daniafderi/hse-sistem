<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValidationSafetyPatrol extends Model
{
    protected $fillable = [
        'safety_patrol_id',
        'validator_id',
        'status',
        'komentar'
    ];

    public function report()
    {
        return $this->belongsTo(DailySafetyPatrol::class, 'safety_patrol_id');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validator_id');
    }
}
