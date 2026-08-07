<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(DefaultSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(ItemSeeder::class);
    }
}
