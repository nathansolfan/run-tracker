<?php

namespace Database\Seeders;

use App\Models\Run;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create runs for the first user in the database (or create one if none exists)
        $testUser = User::first() ?? User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // create 15 runs for the user
        Run::factory()
        ->count(15)
        ->for($testUser)
        ->create();

        // Additionally, create runs for a specific user by email (your account)
        $myUser = User::where('email','max@gmail.com')->first();

        // only create runs if this user exist
        if ($myUser) {
            Run::factory()
            ->count(15)
            ->for($myUser)
            ->create();
        }

    }
}
