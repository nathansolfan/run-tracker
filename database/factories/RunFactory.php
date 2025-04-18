<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Run>
 */
class RunFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        // generate random coordinates for NYC
        $centerLat = 40.7128;
        $centerLng = -74.0060;
        $latRange = 0.05;
        $lngRange = 0.05;

        // create simulated route data
        $routePoints = [];
        $pointCount = $this->faker->numberBetween(20,50);

        $startLat = $this->faker->latitude($centerLat - $latRange, $centerLat + $latRange);
        $startLng = $this->faker->longitude($centerLng - $lngRange, $centerLat + $lngRange);

        for ($i=0; $i < $pointCount ; $i++) { 
            // each new point is slightly offsent from previous one
            $lat = $i === 0 ? $startLat : $routePoints[$i-1][0] + $this->faker->randomFloat(6, -0.002, 0.002);
            $lng = $i === 0 ? $startLng : $routePoints[$i-1][1] + $this->faker->randomFloat(6, -0.002, 0.002);
            $routePoints[] = [$lat,$lng];
        }

        // random distance between 1-10miles
        $distance = $this->faker->randomFloat(2,1,10);

        // duration based on pace 8-12min/mile
        $minPerMile = $this->faker->numberBetween(8,12);
        $durationSeconds = $distance * $minPerMile * 60;

        return [
            'user_id' => User::factory(),
            'date' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'distance' => $distance,
            'duration' => (int)$durationSeconds,
            'notes' => $this->faker->optional(0.7)->sentence(),
            'route_data' => $routePoints,
        ];
    }
}
