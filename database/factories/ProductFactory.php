<?php

namespace Database\Factories;

use App\Models\Product;
use App\Support\Image;
use App\Support\Price;
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
        $price = Price::random();

        return [
            'name' => static::randomFromArray(static::$names),
            'current_price' => $price->getCurrentPrice(),
            'base_price' => $price->getBasePrice(),
            'is_available' => fake()->boolean(),
            'description' => $this->htmlText(),
            'preview_image' => null,
        ];
    }

    public function withImage(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'preview_image' => Image::createFromAbsolutePath(resource_path(static::randomFromArray(static::$images)))->getRelativePath(),
            ];
        });
    }

    public function htmlText(): string
    {
        $paragraphs = $this->faker->paragraphs(random_int(2, 6));
        $text = "";

        foreach ($paragraphs as $para) {
            $text .= "<p>{$para}</p>";
        }

        return $text;
    }

    private static function randomFromArray(array $array): string
    {
        return $array[random_int(0, count($array) - 1)];
    }
}
