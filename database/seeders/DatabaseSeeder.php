<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('admin'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('currencies')->insert([
            [
                'name' => 'Hryvnia',
                'iso_code' => 'UAH',
            ],
            [
                'name' => 'Dollar',
                'iso_code' => 'USD',
            ],
            [
                'name' => 'Euro',
                'iso_code' => 'EUR',
            ],
        ]);

        DB::table('products')->insert([
            [
                'title' => 'Тестовий товар №1',
                'price' => 500.50,
                'currency_id' => 1, // UAH
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Тестовий товар №2',
                'price' => 300,
                'currency_id' => 2, // USD
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Тестовий товар №3',
                'price' => 600,
                'currency_id' => 3, // EUR
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
