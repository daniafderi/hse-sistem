<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImageSafetyPatrol extends Model
{
    use HasFactory;

    protected $table = 'image_safety_patrols';
    protected $guarded = ['id'];
    public $timestamps = true;

    public function safetyPatrol() {
        return $this->belongsTo(DailySafetyPatrol::class, 'safety_patrol_id');
    }
}
