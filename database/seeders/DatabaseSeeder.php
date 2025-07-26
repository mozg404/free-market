<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\StockItem;
use App\Models\Shop;
use App\Models\User;
use Database\Factories\StockItemFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create(['email' => 'user@gmail.com']);

        Product::factory()
            ->withImage()
            ->count(10)
            ->has(StockItem::factory()->available()->count(random_int(5,10)))
            ->create(['user_id' => $user->id]);

        User::factory()
            ->has(
                Product::factory()
                    ->withImage()
                    ->count(random_int(0,3))
                    ->has(StockItem::factory()->count(random_int(0,3)))
            )
            ->count(3)
            ->create();
    }
}
