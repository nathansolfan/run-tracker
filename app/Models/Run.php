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
        'date',
        'notes',
        'route_data'

    ];

    protected $casts = [
        'date' => 'datetime',
        'route_data' => 'array', // Add this line to cast the field

    ];

    /**
     * Get the user that owns the run.
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get pace in minutes per mile
     */
    public function getPaceAttribute()
    {
        if (!$this->distance || !$this->duration) {
            return null;
        }
        // Calculate minutes per mile
        return $this->duration / 60 / $this->distance;
    }

    /**
     * Format pace for display (e.g., "8:30 min/mile")
     */
    public function getFormattedPaceAttribute()
    {
        $pace = $this->pace;
        if ($pace === null) {
            return 'N/A';
        }

        $minutes = floor($pace);
        $seconds = round(($pace - $minutes) * 60);

        return $minutes . ':' . str_pad($seconds, 2, '0', STR_PAD_LEFT) . ' min/mile';
    }

    /**
     * Format duration for display (e.g., "1:30:45")
     */
    public function getFormattedDurationAttribute()
    {
        $hours = floor($this->duration / 3600);
        $minutes = floor(($this->duration % 3600) / 60);
        $seconds = $this->duration % 60;

        if ($hours > 0) {
            return $hours . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT) . ':' . str_pad($seconds, 2, '0', STR_PAD_LEFT);
        }

        return $minutes . ':' . str_pad($seconds, 2, '0', STR_PAD_LEFT);
    }
}
