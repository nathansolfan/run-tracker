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
        // create the first test user if noone exist
        $user = User::first() ?? User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // create 15 runs for the user
        Run::factory()
        ->count(15)
        ->for($user)
        ->create();
    }
}
