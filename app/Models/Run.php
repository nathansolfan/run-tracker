<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Run extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'distance',
        'duration',
        'pace',
        'average_speed',
        'started_at',
        'ended_at',
        'route_data',
        'elevation_gain',
        'weather_conditions',
        'notes',
    ];

    protected $casts = [
        'route_data' => 'array',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * Get the user that owns the run.
     */

     public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate pace when accessing the model
     */
    public function getPaceAttribute()
    {
        if (!$this->distance || !$this->duration) {
            return null;
        }
        // Calculate minutes per mile/km
        return $this->duration / 60 / $this->distance;
    }


}
