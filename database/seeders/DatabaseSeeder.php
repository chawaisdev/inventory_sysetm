<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin',
            'user_type' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin@123'),
            'address' => 'Admin Address',  // optional
            'phone' => '1234567890',       // optional
        ]);

        // Customers (3)
        User::create([
            'name' => 'Customer One',
            'user_type' => 'Customer',
            'email' => 'customer1@gmail.com',
            'password' => bcrypt('customer123'),
            'address' => '123 Customer St, City A',
            'phone' => '111-222-3333',
        ]);
        User::create([
            'name' => 'Customer Two',
            'user_type' => 'Customer',
            'email' => 'customer2@gmail.com',
            'password' => bcrypt('customer123'),
            'address' => '456 Customer Rd, City B',
            'phone' => '222-333-4444',
        ]);
        User::create([
            'name' => 'Customer Three',
            'user_type' => 'Customer',
            'email' => 'customer3@gmail.com',
            'password' => bcrypt('customer123'),
            'address' => '789 Customer Ave, City C',
            'phone' => '333-444-5555',
        ]);

        // Suppliers (3)
        User::create([
            'name' => 'Supplier One',
            'user_type' => 'Supplier',
            'email' => 'supplier1@gmail.com',
            'password' => bcrypt('supplier123'),
            'address' => '123 Supplier St, City X',
            'phone' => '444-555-6666',
        ]);
        User::create([
            'name' => 'Supplier Two',
            'user_type' => 'Supplier',
            'email' => 'supplier2@gmail.com',
            'password' => bcrypt('supplier123'),
            'address' => '456 Supplier Rd, City Y',
            'phone' => '555-666-7777',
        ]);
        User::create([
            'name' => 'Supplier Three',
            'user_type' => 'Supplier',
            'email' => 'supplier3@gmail.com',
            'password' => bcrypt('supplier123'),
            'address' => '789 Supplier Ave, City Z',
            'phone' => '666-777-8888',
        ]);
    }
}
