<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ServiceRequest extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'submission_date' => 'date',
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


}
