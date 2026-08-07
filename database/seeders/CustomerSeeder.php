<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'first_name' => 'Jh',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'phone' => '1234567890',
            'whatsapp' => '1234567890',
            'address' => '123 Main St',
            'city' => 'AnyTown',
            'state' => 'AnyState',
            'country' => 'AnyCountry',
            'pin_code' => '123456',
            'active' => 1,
            'balance' => 0,
        ]);
    }
}
