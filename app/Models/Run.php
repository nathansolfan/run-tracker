<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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


}
