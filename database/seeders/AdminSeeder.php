<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // // this is if you want to use the factory for creating multiple users admin
        // Admin::factory()->count(10)->create();


        // // this is if you want to create a single admin 
        // // but if i create a user with the same email it will throw an error

        //   Admin::create([
        //     'name' => 'Super Admin',
        //     'email' => 'admin@example.com',
        //     'password' => Hash::make('123456'),
        // ]);


        Admin::updateOrCreate(['email' => 'admin@gmail.com'], [
            'name' => 'Super Admin',
            'password' => Hash::make('123456'),
        ]);
    }
}
