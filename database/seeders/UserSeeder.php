<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->has(
                Shop::factory()->count(2)->has(
                    Product::factory()->count(3)
                )
            )
            ->count(5)
            ->create();
    }
}
