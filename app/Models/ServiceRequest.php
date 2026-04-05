<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ServiceRequest extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->tracking_token = strtoupper(\Illuminate\Support\Str::random(6));
        });
    }

    protected $casts = [
        'submission_date' => 'date',
        'deadline_date' => 'date',
        'picked_up_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function releasedBy()
    {
        return $this->belongsTo(User::class, 'released_by');
    }

    // Accessor: Cek apakah mendekati deadline (< 2 hari)
    public function getIsUrgentAttribute()
    {
        if ($this->status === 'completed')
            return false;

        $deadline = Carbon::parse($this->deadline_date);
        return $deadline->diffInDays(now()) <= 2 && $deadline->isFuture();
    }

    // Accessor: Cek status terlambat dinamis (real-time check)
    public function getIsOverdueCalcAttribute()
    {
        return $this->status !== 'completed' && Carbon::now()->startOfDay()->gt($this->deadline_date);
    }
}
