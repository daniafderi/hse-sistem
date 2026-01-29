<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProjectSafety extends Model
{
    use HasFactory;

    protected $table= "project_safeties";
    protected $guarded = ['id'];
    protected $fillable = ['nama','lokasi','status','tanggal_mulai','tanggal_selesai','deskripsi'];
    protected $casts = [
        'status' => 'string'
    ];
    public $timestamps = true;

    protected static function booted()
    {
        static::creating(function ($projectSafety) {
            $projectSafety->user_id = Auth::user()->id;
        });
    }

    public function dailySafetyPatrol() {
        return $this->hasMany(DailySafetyPatrol::class);
    }
}
