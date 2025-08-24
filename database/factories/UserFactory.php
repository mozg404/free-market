<?php

namespace Database\Factories;

use App\Support\Image;
use App\Support\RatingCalculator;
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
    protected static string $password = '12345678';

    private static array $avatars = [
        'demo/avatars/0.jpg',
        'demo/avatars/1.jpg',
        'demo/avatars/2.jpg',
        'demo/avatars/3.jpg',
        'demo/avatars/4.jpg',
        'demo/avatars/5.jpg',
        'demo/avatars/6.jpg',
        'demo/avatars/7.jpg',
        'demo/avatars/8.jpg',
        'demo/avatars/9.jpg',
        'demo/avatars/10.jpg',
        'demo/avatars/11.jpg',
        'demo/avatars/12.jpg',
        'demo/avatars/13.jpg',
        'demo/avatars/14.jpg',
        'demo/avatars/15.jpg',
        'demo/avatars/16.jpg',
        'demo/avatars/17.jpg',
        'demo/avatars/18.jpg',
        'demo/avatars/19.jpg',
        'demo/avatars/20.jpg',
        'demo/avatars/21.jpg',
        'demo/avatars/22.jpg',
        'demo/avatars/23.jpg',
        'demo/avatars/24.jpg',
        'demo/avatars/25.jpg',
        'demo/avatars/26.jpg',
        'demo/avatars/27.jpg',
        'demo/avatars/28.webp',
        'demo/avatars/29.jpg',
        'demo/avatars/30.jpg',
        'demo/avatars/31.jpg',
    ];

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

    public function withCalculatedRating(int $positiveCounter, int $negativeCounter): static
    {
        return $this->state(function (array $attributes) use ($positiveCounter, $negativeCounter) {
            return [
                'positive_feedbacks_count' => $positiveCounter,
                'negative_feedbacks_count' => $negativeCounter,
                'seller_rating' => RatingCalculator::calculate($positiveCounter, $negativeCounter),
            ];
        });
    }

    public function withPublishedAvatar(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'avatar' => Image::createFromAbsolutePath(resource_path($this->faker->randomElement(static::$avatars)))->publishIfTemporary()->getRelativePath(),
            ];
        });
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
