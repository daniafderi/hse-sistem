<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SafetyBriefing extends Model
{
    use HasFactory;

    protected $table = 'safety_briefings';
    protected $guarded = ['id'];
    protected $fillable = ['tempat', 'pekerjaan', 'jumlah_peserta', 'catatan'];
    public $timestamps = true;

    protected static function booted()
    {
        static::creating(function ($safetyBriefing) {
            $safetyBriefing->user_id = Auth::user()->id;
        });
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function images() {
        return $this->hasMany(ImageSafetyBriefing::class);
    }
}
