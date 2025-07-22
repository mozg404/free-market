<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create(['email' => 'user@gmail.com']);

        Product::factory()->withImage()->count(10)->create(['user_id' => $user->id]);

//        User::factory()
//            ->has(
//                Shop::factory()->count(2)->has(
//                    Product::factory()->count(30)
//                )
//            )
//            ->create([
//                'email' => 'user@gmail.com',
//            ]);
//
//        User::factory()
//            ->has(
//                Shop::factory()->count(rand(1, 3))->has(
//                    Product::factory()->count(rand(2, 10))
//                )
//            )
//            ->count(4)
//            ->create();

//        $this->call([
//            UserSeeder::class,
//        ]);
    }
}
