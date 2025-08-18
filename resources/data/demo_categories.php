<?php

use App\Enum\FeatureType;

return [
    'games' => [
        'name' => 'Игры и DLC',
    ],

    'software' => [
        'name' => 'Софт',
        'children' => [
            'office' => [
                'name' => 'Офис',
            ],
            'graphics' => [
                'name' => 'Графика',
            ],
            'antivirus' => [
                'name' => 'Антивирусы',
            ],
            'programming' => [
                'name' => 'Программирование',
            ],
        ]
    ],

    'windows' => [
        'name' => 'Windows',
    ],

    'certificates' => [
        'name' => 'Валюта',
    ],

    'subscriptions' => [
        'name' => 'Подписки',
    ],
];