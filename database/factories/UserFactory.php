<?php

namespace Database\Factories;

use App\Support\Image;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static string $password = '123456';

    private static array $avatars = [
        'demo/avatars/avatar1.jpg',
        'demo/avatars/avatar2.jpg',
        'demo/avatars/avatar3.jpg',
        'demo/avatars/avatar4.jpg',
        'demo/avatars/avatar5.jpg',
        'demo/avatars/avatar6.jpg',
        'demo/avatars/avatar7.jpg',
        'demo/avatars/avatar8.jpg',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $createdAt = $this->faker->dateTimeBetween('-1 year');

        return [
            'name' => fake()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make(static::$password),
            'remember_token' => Str::random(10),
            'created_at' => $createdAt,
            'updated_at' => $this->faker->dateTimeBetween($createdAt),
        ];
    }

    public function withAvatar(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'avatar' => Image::createFromAbsolutePath(resource_path($this->faker->randomElement(static::$avatars)))->getRelativePath(),
            ];
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
