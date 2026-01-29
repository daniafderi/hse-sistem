<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImageSafetyBriefing extends Model
{
    use HasFactory;

    protected $table = 'image_safety_briefings';
    protected $guarded = ['id'];
    public $timestamps = true;

    public function safetyBriefing() {
        return $this->belongsTo(SafetyBriefing::class, 'safety_briefing_id');
    }
}
