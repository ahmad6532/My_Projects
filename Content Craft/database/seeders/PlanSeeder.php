<?php

namespace Database\Seeders;

use App\Models\Plans;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plans::insert([
            [
                'name' => 'Free Plan',
                'articles' => 5,
                'amount' => 0,
                'createdAt' => now(),
                'updatedAt' => now()
            ],
            [
                'name' => 'Basic Plan',
                'articles' => 5,
                'amount' => 50,
                'createdAt' => now(),
                'updatedAt' => now()
            ],
            [
                'name' => 'Platinum Plan',
                'articles' => 10,
                'amount' => 70,
                'createdAt' => now(),
                'updatedAt' => now()
            ],
            [
                'name' => 'Gold Plan',
                'articles' => 15,
                'amount' => 100,
                'createdAt' => now(),
                'updatedAt' => now()
            ]
        ]);
    }
}
