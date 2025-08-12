<?php

namespace Database\Factories;

use App\Enum\ProductStatus;
use App\Models\Product;
use App\Models\User;
use App\Support\Image;
use App\Support\Price;
use App\Support\TextGenerator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    private static array $images = [
        'demo/products_images/1_win10.jpg',
        'demo/products_images/2_office_professional.jpg',
        'demo/products_images/3_win11.jpg',
        'demo/products_images/4_adobe_creative.jpg',
        'demo/products_images/5_jetbrains.jpg',
        'demo/products_images/6_phpstorm.jpg',
        'demo/products_images/7_webstorm.jpg',
        'demo/products_images/8_itunes.jpg',
        'demo/products_images/9_eset_nod32.jpg',
        'demo/products_images/10_autocad.jpg',
    ];

    private static array $names = [
        'Ключ Autodesk AutoCAD 2026',
        'Windows 11 Pro (Профессиональная)',
        'Windows 11 IoT Enterprise 2024 LTSC (Корпоративная)',
        'Windows 10 Enterprise 2021 LTSC',
        'Ключ активации Microsoft Windows 10 Pro, бессрочный (активация онлайн), виндовс 10 про бессрочный',
        'Ключ активации Windows 10 Pro x32/64, электронный, мультиязычный, бессрочный',
        'Выделенный ПРОКСИ Outline ключ НАВСЕГДА! Без скоростных ограничений по трафику',
        'ПРОКСИ VLESS для защищенного доступа в интернет НАВСЕГДА (без ограничения скорости)',
        'Подарочная карта Apple iTunes, Россия, 500 руб, электронный ключ',
        'Подарочная карта Apple iTunes, Россия, 600 руб, электронный ключ',
        'Microsoft Office 2019 Professional Plus LTSC / Электронный ключ активации / Бессрочная лицензия / Гарантия',
        'Microsoft Office 2021 Professional Plus Retail | Электронный ключ | Бессрочный | 1 ПК | Без привязки к учетной записи',
        'Microsoft Office 2024 Professional Plus LTSC Электронный ключ активации Бессрочная лицензия (без привязки к учетной записи)',
    ];

    public function definition(): array
    {
        $createdAt = $this->faker->dateTimeBetween('-1 year', 'now');
        $price = Price::random();

        return [
            'user_id' => User::factory(),
            'name' => $this->faker->randomElement(static::$names),
            'current_price' => $price->getCurrentPrice(),
            'base_price' => $price->getBasePrice(),
            'status' => fake()->randomElement(ProductStatus::cases())->value,
            'description' => TextGenerator::paragraphs(include resource_path('data/demo_product_descriptions.php'), random_int(3, 7)),
            'instruction' => TextGenerator::paragraphs(include resource_path('data/demo_product_instructions.php'), random_int(1, 4)),
            'preview_image' => null,
            'created_at' => $createdAt,
            'updated_at' => $this->faker->dateTimeBetween($createdAt),
        ];
    }

    public function isDraft(): Factory
    {
        return $this->state(fn (array $attributes) => ['status' => ProductStatus::DRAFT->value]);
    }

    public function isActive(): Factory
    {
        return $this->state(fn (array $attributes) => ['status' => ProductStatus::ACTIVE->value]);
    }

    public function isPaused(): Factory
    {
        return $this->state(fn (array $attributes) => ['status' => ProductStatus::PAUSED->value]);
    }

    public function fromDemo(?array $data = null): Factory
    {
        return $this->state(function (array $attributes) use ($data) {
            // Если нет данных или нет списка - возвращаем пустой массив (не изменяем атрибуты)
            if (empty($data['list'])) {
                return [];
            }

            $demoItem = $this->faker->randomElement($data['list']);

            // Обработка name (может быть строкой или массивом)
            $name = is_array($demoItem['name'] ?? null)
                ? $this->faker->randomElement($demoItem['name'])
                : ($demoItem['name'] ?? $this->faker->sentence(2));

            // Обработка image (может быть строкой, массивом или отсутствовать)
            $imagePath = null;
            if (isset($demoItem['image'])) {
                $imagePath = is_array($demoItem['image'])
                    ? $this->faker->randomElement($demoItem['image'])
                    : $demoItem['image'];
            }

            return array_filter([
                'name' => TextGenerator::decoratedText($name, $data['name_modifiers'], random_int(0, 2), $this->faker->randomElement(['. ', ' | '])),
                'preview_image' => $imagePath
                    ? Image::createFromAbsolutePath($imagePath)->getRelativePath()
                    : null,
            ]);
        });
    }

    public function withPrice(Price $price): ProductFactory|Factory
    {
        return $this->state(function (array $attributes) use ($price) {
            return [
                'current_price' => $price->getCurrentPrice(),
                'base_price' => $price->getBasePrice(),
            ];
        });
    }

    public function withImage(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'preview_image' => Image::createFromAbsolutePath(resource_path($this->faker->randomElement(static::$images)))->getRelativePath(),
            ];
        });
    }
}
