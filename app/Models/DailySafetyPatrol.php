<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySafetyPatrol extends Model
{
    use HasFactory;

    protected $table = 'daily_safety_patrols';
    protected $guarded = ['id'];
    protected $fillable = ['project_safety_id','tanggal','permit','jam_kerja','jumlah_pekerja','reward','nearmiss','punishment','kecelakaan','deskripsi', 'status_validasi'];
    public $timestamps = true;

    public function project() {
        return $this->belongsTo(ProjectSafety::class, 'project_safety_id');
    }

    public function users() {
        return $this->belongsToMany(User::class, 'user_daily_safety_patrols',
        'daily_safety_patrol_id',
        'user_id');
    }

    public function images() {
        return $this->hasMany(ImageSafetyPatrol::class);
    }

    public function validations()
    {
        return $this->hasMany(ValidationSafetyPatrol::class, 'safety_patrol_id');
    }

    public function lastValidation()
    {
        return $this->hasOne(ValidationSafetyPatrol::class, 'safety_patrol_id')->latest();
    }
}
