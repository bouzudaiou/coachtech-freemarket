<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'taro',
            'email' => 'taro@abc.com',
            'password' => bcrypt('123abc45'),
            'postal_code' => '123-4567',
            'email_verified_at' => now(),
            'address' => '東京都渋谷区1-2-3',
        ]);
    }
}
