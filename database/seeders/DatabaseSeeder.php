<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed standard demo user
        $user = User::create([
            'name' => 'Demo User',
            'email' => 'user@fit.my',
            'password' => Hash::make('password'),
            'age' => 25,
            'height' => 175,
            'weight' => 70,
            'gender' => 'male',
            'calorie_intake' => 2150,
            'user_type' => 'user',
        ]);

        // 2. Seed admin user
        User::create([
            'name' => 'Admin Fit.my',
            'email' => 'admin@fit.my',
            'password' => Hash::make('password'),
            'age' => 30,
            'height' => 180,
            'weight' => 75,
            'gender' => 'male',
            'calorie_intake' => 2400,
            'user_type' => 'admin',
        ]);

        // 3. Seed meals and requests
        $this->call(MealSeeder::class);

        // 4. Seed 5 days of past reports for demo user
        $pastDays = [
            ['daysAgo' => 4, 'b' => 420, 'l' => 650, 'd' => 580],
            ['daysAgo' => 3, 'b' => 350, 'l' => 590, 'd' => 610],
            ['daysAgo' => 2, 'b' => 400, 'l' => 710, 'd' => 540],
            ['daysAgo' => 1, 'b' => 380, 'l' => 620, 'd' => 690],
            ['daysAgo' => 0, 'b' => 360, 'l' => 550, 'd' => 520],
        ];

        foreach ($pastDays as $item) {
            $total = $item['b'] + $item['l'] + $item['d'];
            Report::create([
                'user_id' => $user->id,
                'date' => Carbon::today()->subDays($item['daysAgo'])->toDateString(),
                'breakfast_total' => $item['b'],
                'lunch_total' => $item['l'],
                'dinner_total' => $item['d'],
                'total' => $total,
            ]);
        }
    }
}
