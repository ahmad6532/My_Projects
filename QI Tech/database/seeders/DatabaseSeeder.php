<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(Initial_Basic_seeder::class);
        $this->call(DatabaseTableSeeder::class);
        $this->call(AdminAccountSeeder::class);
        $this->call(RemoteDefaultUserSeeder::class);
        $this->call(DefaultCardSeeder::class);
        // $this->call(DMDSeeder::class);
        $this->call(FiveWhysSeeder::class);
        $this->call(LfpseFormSeeder::class);
        $this->call(LfpseOptionsSeeder::class);
        $this->call(VPMSeeder::class);
        $this->call(VTMSeeder::class);
    }
}
