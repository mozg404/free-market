<?php

namespace Database\Factories;

use App\Enum\FeatureType;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feature>
 */
class FeatureFactory extends Factory
{
    /**
     * @throws \JsonException
     */
    public function definition(): array
    {
//        $types = FeatureType::values();
        $types = ['select'];
        $type = $this->faker->randomElement($types);
        $options = null;

        if (in_array($type, ['select', 'multiselect'])) {
            $options = [];

            foreach ($this->faker->words(5) as $word) {
                $options[Str::slug($word)] = $word;
            }
        }

        return [
            'category_id' => Category::factory(),
            'name' => $this->faker->unique()->word(), // Добавляем заполнение поля name
            'key' => $this->faker->unique()->slug(),
            'type' => $type,
            'options' => $options,
        ];
    }
}
