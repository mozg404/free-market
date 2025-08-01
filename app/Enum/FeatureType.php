<?php

namespace App\Enum;

enum FeatureType: string
{
    case SELECT = 'select';
    case TEXT = 'text';
    case NUMBER = 'number';
    case CHECK = 'check';

    public static function names(): array
    {
        return [
            self::SELECT->value => 'Выпадающий список',
            self::TEXT->value => 'Текст',
            self::NUMBER->value => 'Число',
            self::CHECK->value => 'Да/Нет',
        ];
    }
}
