<?php

use App\Enum\FeatureType;

return [
    'keys' => [
        'name' => 'Ключи активации',
        'title' => 'Ключи активации',
        'children' => [
            'keys/games' => [
                'name' => 'Игры и DLC',
                'title' => 'Игры и DLC',
            ],
            'keys/software' => [
                'name' => 'Программы',
                'title' => 'Программы',
                'children' => [
                    'keys/software/office' => [
                        'name' => 'Офис',
                        'title' => 'Ключи для офисных программ',
                    ],
                    'keys/software/graphics' => [
                        'name' => 'Графика и 3D',
                        'title' => 'Ключи для графических и 3D редакторов',
                    ],
                    'keys/software/programming' => [
                        'name' => 'Программирование',
                        'title' => 'Ключи для сред разработки',
                    ],
                    'keys/software/antivirus' => [
                        'name' => 'Антивирусы',
                        'title' => 'Ключи для антивирусов',
                    ],
                ],
            ],
            'keys/windows' => [
                'name' => 'Windows',
                'title' => 'Ключи активации Windows',
            ],
        ]
    ],

    'certificates' => [
        'name' => 'Сертификаты и карты пополнения',
        'title' => 'Сертификаты, подарочные карты и пополнение счета',
    ],

    'game-currency' => [
        'name' => 'Игровая валюта',
        'title' => 'Игровая валюта',
    ],

    'subscriptions' => [
        'name' => 'Подписки',
        'title' => 'Подписки',
        'children' => [
            'subscriptions/games' => [
                'name' => 'Игровые подписки',
                'title' => 'Игровые подписки',
            ],
            'subscriptions/ai' => [
                'name' => 'Нейросети',
                'title' => 'Подписки на нейросети',
            ],
            'subscriptions/streaming' => [
                'name' => 'Стриминговые сервисы',
                'title' => 'Подписки на стриминговые сервисы',
            ],
            'subscriptions/social' => [
                'name' => 'Общение и соц. сети',
                'title' => 'Подписки на платформы для общения и социальные сети',
            ],
            'subscriptions/software' => [
                'name' => 'Программы',
                'title' => 'Программы',
                'children' => [
                    'subscriptions/software/bundles' => [
                        'name' => 'Комплекты',
                        'title' => 'Единые подписки на программное обеспечение',
                    ],
                    'subscriptions/software/office' => [
                        'name' => 'Офис',
                        'title' => 'Ключи для офисных программ',
                    ],
                    'subscriptions/software/graphics' => [
                        'name' => 'Графика и 3D',
                        'title' => 'Ключи для графических и 3D редакторов',
                    ],
                    'subscriptions/software/programming' => [
                        'name' => 'Программирование',
                        'title' => 'Ключи для сред разработки',
                    ],
                    'subscriptions/software/antivirus' => [
                        'name' => 'Антивирусы',
                        'title' => 'Подписки на антивирусные программы',
                    ],
                ],
            ],
        ],
    ],
];