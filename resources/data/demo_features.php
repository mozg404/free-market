<?php

use App\Enum\FeatureType;

return [

    // ------------------------------------------------------
    // Игры
    // ------------------------------------------------------

    'games' => [
        [
            'type' => FeatureType::SELECT,
            'name' => 'Жанры',
            'options' => [
                'ekshen' => 'Экшен',
                'priklyucheniya' => 'Приключения',
                'rolevye_igry_rpg' => 'Ролевые игры (RPG)',
                'strategii' => 'Стратегии',
                'shutery' => 'Шутеры',
                'simulyatory' => 'Симуляторы',
                'gonki' => 'Гонки',
                'horror' => 'Хоррор',
                'otkrytyy_mir' => 'Открытый мир',
                'vyzhivanie' => 'Выживание',
                'pesochnitsa' => 'Песочница',
                'moba' => 'MOBA',
                'mmo' => 'MMO',
                'boyevik' => 'Боевик',
                'faytingi' => 'Файтинги',
                'golovolomki' => 'Головоломки',
                'rogalik_roguelike' => 'Рогалик (Roguelike)',
                'tacticheskie_igry' => 'Тактические игры',
                'stels-ekshen' => 'Стелс-экшен',
                'vizualnye_novelly' => 'Визуальные новеллы',
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Игровая платформа',
            'options' => [
                'steam' => 'Steam',
                'epic_games' => 'Epic Games',
                'origin' => 'Origin',
                'uplay' => 'Ubisoft Connect (Uplay)',
                'gog' => 'GOG',
                'battle_net' => 'Battle.net',
                'xbox_live' => 'Xbox Live',
                'playstation_network' => 'PlayStation Network (PSN)',
                'nintendo_eshop' => 'Nintendo eShop',
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Тип издания',
            'options' => [
                'standart' => 'Стандартное издание',
                'deluxe' => 'Deluxe издание',
                'ultimate' => 'Ultimate издание',
                'gold' => 'Gold Edition',
                'premium' => 'Premium Edition',
                'season_pass' => 'Season Pass',
                'bundle' => 'Бандл (набор DLC)',
                'early_access' => 'Early Access',
                'free_to_play' => 'Free-to-play',
                'digital_only' => 'Только цифровая версия',
            ]
        ],
    ],

    // ------------------------------------------------------
    // Игровые платформы и подписки
    // ------------------------------------------------------

    'game_services' => [
        [
            'type' => FeatureType::SELECT,
            'name' => 'Тип сервиса',
            'options' => [
                'subscription' => 'Подписка',
                'game_pass' => 'Игровой пропуск',
                'cloud_gaming' => 'Облачный гейминг',
                'early_access' => 'Ранний доступ',
                'premium' => 'Премиум-аккаунт'
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Платформа',
            'options' => [
                'steam' => 'Steam',
                'epic_games' => 'Epic Games',
                'origin' => 'Origin',
                'uplay' => 'Ubisoft Connect',
                'gog' => 'GOG',
                'battle_net' => 'Battle.net',
                'xbox_live' => 'Xbox Live',
                'playstation_network' => 'PlayStation Network',
                'nintendo_eshop' => 'Nintendo eShop',
                'xbox_game_pass' => 'Xbox Game Pass',
                'ea_play' => 'EA Play',
                'ubisoft_plus' => 'Ubisoft+',
                'playstation_plus' => 'PlayStation Plus',
                'nintendo_switch_online' => 'Nintendo Switch Online',
                'vkontakte_games' => 'ВКонтакте Игры',
                'mygames' => 'MY.GAMES'
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Период подписки',
            'options' => [
                '1_month' => '1 месяц',
                '3_months' => '3 месяца',
                '6_months' => '6 месяцев',
                '1_year' => '1 год',
                'lifetime' => 'Пожизненная'
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Особенности',
            'options' => [
                'cloud_saves' => 'Облачные сохранения',
                'exclusive_content' => 'Эксклюзивный контент',
                'discounts' => 'Скидки на игры',
                'multiplayer' => 'Доступ к мультиплееру',
                'free_games' => 'Бесплатные игры'
            ]
        ],
        [
            'type' => FeatureType::TEXT,
            'name' => 'Регион активации',
            'options' => []
        ]
    ],


    // ------------------------------------------------------
    // Операционные системы
    // ------------------------------------------------------

    'os' => [
        [
            'type' => FeatureType::SELECT,
            'name' => 'Тип ОС',
            'options' => [
                'windows' => 'Windows',
                'macos' => 'macOS',
                'linux' => 'Linux',
                'android' => 'Android',
                'ios' => 'iOS'
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Версия',
            'options' => [
                'home' => 'Домашняя',
                'pro' => 'Профессиональная',
                'enterprise' => 'Корпоративная',
                'education' => 'Для образования',
                'server' => 'Серверная'
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Разрядность',
            'options' => [
                '32bit' => '32-битная',
                '64bit' => '64-битная',
                'arm' => 'ARM-версия'
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Дополнительные функции',
            'options' => [
                'activated' => 'Активирована',
                'fresh_install' => 'Чистая установка',
                'with_drivers' => 'С драйверами',
                'multi_language' => 'Многоязычная',
                'oem' => 'OEM-версия'
            ]
        ],
    ],

    // ------------------------------------------------------
    // ИИ-сервисы
    // ------------------------------------------------------

    'ai' => [
        [
            'type' => FeatureType::SELECT,
            'name' => 'Тип сервиса',
            'options' => [
                'chatbot' => 'Чат-бот',
                'image_generation' => 'Генерация изображений',
                'text_analysis' => 'Анализ текста',
                'voice_assistant' => 'Голосовой ассистент',
                'video_processing' => 'Обработка видео'
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Провайдер',
            'options' => [
                'openai' => 'OpenAI',
                'midjourney' => 'Midjourney',
                'stability_ai' => 'Stability AI',
                'anthropic' => 'Anthropic',
                'deepmind' => 'DeepMind'
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Подписка',
            'options' => [
                'free' => 'Бесплатная',
                'basic' => 'Базовая',
                'pro' => 'Профессиональная',
                'enterprise' => 'Корпоративная',
                'pay_as_you_go' => 'Pay-as-you-go'
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Возможности',
            'options' => [
                'api_access' => 'Доступ к API',
                'priority_support' => 'Приоритетная поддержка',
                'higher_limits' => 'Повышенные лимиты',
                'custom_models' => 'Кастомные модели',
                'commercial_use' => 'Коммерческое использование'
            ]
        ],
        [
            'type' => FeatureType::TEXT,
            'name' => 'Кредиты/Лимиты',
            'options' => []
        ]
    ],

    // ------------------------------------------------------
    // Медиа и стриминг
    // ------------------------------------------------------

    'media' => [
        [
            'type' => FeatureType::SELECT,
            'name' => 'Тип сервиса',
            'options' => [
                'music' => 'Музыка',
                'video' => 'Видеостриминг',
                'podcasts' => 'Подкасты',
                'audiobooks' => 'Аудиокниги',
                'ebooks' => 'Электронные книги'
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Провайдер',
            'options' => [
                'spotify' => 'Spotify',
                'apple_music' => 'Apple Music',
                'youtube_premium' => 'YouTube Premium',
                'netflix' => 'Netflix',
                'amazon_prime' => 'Amazon Prime'
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Подписка',
            'options' => [
                'free' => 'Бесплатная',
                'individual' => 'Индивидуальная',
                'family' => 'Семейная',
                'student' => 'Студенческая',
                'annual' => 'Годовая'
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Качество контента',
            'options' => [
                'sd' => 'SD',
                'hd' => 'HD',
                'full_hd' => 'Full HD',
                '4k' => '4K',
                'hdr' => 'HDR'
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Регион',
            'options' => [
                'ru' => 'Россия',
                'us' => 'США',
                'eu' => 'Европа',
                'asia' => 'Азия',
                'global' => 'Глобальная'
            ]
        ]
    ],

    // ------------------------------------------------------
    // Соцсети и коммуникации
    // ------------------------------------------------------

    'social' => [
        [
            'type' => FeatureType::SELECT,
            'name' => 'Тип сервиса',
            'options' => [
                'social_network' => 'Соцсеть',
                'messenger' => 'Мессенджер',
                'forum' => 'Форум',
                'dating' => 'Знакомства',
                'blogging' => 'Блогинг'
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Платформа',
            'options' => [
                'telegram' => 'Telegram',
                'whatsapp' => 'WhatsApp',
                'vk' => 'ВКонтакте',
                'discord' => 'Discord',
                'instagram' => 'Instagram'
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Тип аккаунта',
            'options' => [
                'personal' => 'Личный',
                'business' => 'Бизнес',
                'premium' => 'Премиум',
                'verified' => 'Верифицированный',
                'creator' => 'Для создателей'
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Длительность',
            'options' => [
                '1_month' => '1 месяц',
                '3_months' => '3 месяца',
                '6_months' => '6 месяцев',
                '1_year' => '1 год',
                'lifetime' => 'Пожизненная'
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Дополнительно',
            'options' => [
                'ads_free' => 'Без рекламы',
                'extra_storage' => 'Доп. хранилище',
                'analytics' => 'Аналитика',
                'multi_device' => 'Мультиустройство',
                'exclusive_content' => 'Эксклюзивный контент'
            ]
        ]
    ],

    // ------------------------------------------------------
    // Программное обеспечение
    // ------------------------------------------------------

    'work_soft' => [
        [
            'type' => FeatureType::SELECT,
            'name' => 'Тип ПО',
            'options' => [
                'office' => 'Офисное',
                'graphics' => 'Графика',
                'video_edit' => 'Видеомонтаж',
                'development' => 'Разработка',
                'security' => 'Безопасность'
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Продукт',
            'options' => [
                'microsoft_office' => 'Microsoft Office',
                'adobe_photoshop' => 'Adobe Photoshop',
                'final_cut' => 'Final Cut Pro',
                'jetbrains' => 'JetBrains Suite',
                'norton' => 'Norton Antivirus'
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Лицензия',
            'options' => [
                'trial' => 'Пробная',
                'home' => 'Домашняя',
                'pro' => 'Профессиональная',
                'enterprise' => 'Корпоративная',
                'education' => 'Образовательная'
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Период',
            'options' => [
                '1_month' => '1 месяц',
                '1_year' => '1 год',
                '3_years' => '3 года',
                'perpetual' => 'Постоянная',
                'subscription' => 'Подписка'
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Версия',
            'options' => [
                'latest' => 'Последняя',
                '2023' => '2023',
                '2022' => '2022',
                '2021' => '2021',
                'legacy' => 'Устаревшая'
            ]
        ]
    ],

    // ------------------------------------------------------
    // VPN и безопасность
    // ------------------------------------------------------

    'vpn_security' => [
        [
            'type' => FeatureType::SELECT,
            'name' => 'Тип сервиса',
            'options' => [
                'vpn' => 'VPN',
                'antivirus' => 'Антивирус',
                'password_manager' => 'Менеджер паролей',
                'firewall' => 'Фаервол',
                'encryption' => 'Шифрование данных'
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Провайдер',
            'options' => [
                'nordvpn' => 'NordVPN',
                'expressvpn' => 'ExpressVPN',
                'kaspersky' => 'Kaspersky',
                'bitdefender' => 'Bitdefender',
                '1password' => '1Password'
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Подписка',
            'options' => [
                '1_month' => '1 месяц',
                '1_year' => '1 год',
                '2_years' => '2 года',
                '3_years' => '3 года',
                'lifetime' => 'Пожизненная'
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Локации',
            'options' => [
                '50+' => '50+ стран',
                '100+' => '100+ стран',
                'specific' => 'Конкретные страны',
                'p2p' => 'P2P-оптимизированные',
                'dedicated' => 'Выделенные IP'
            ]
        ],
        [
            'type' => FeatureType::SELECT,
            'name' => 'Особенности',
            'options' => [
                'no_logs' => 'No-logs политика',
                'kill_switch' => 'Kill Switch',
                'multi_device' => 'Мультиустройство',
                'ad_blocker' => 'Блокировщик рекламы',
                'tor' => 'Поддержка Tor'
            ]
        ]
    ],

    // ------------------------------------------------------
    // Банковские сервисы
    // ------------------------------------------------------

    'banking' => [
        [
            'type' => FeatureType::SELECT,
            'name' => 'Банковский сервис',
            'options' => [
                'tinkoff' => 'Тинькофф',
                'sberbank' => 'СберБанк',
                'alfabank' => 'Альфа-Банк',
                'qiwi' => 'QIWI'
            ]
        ]
    ],

    // ------------------------------------------------------
    // Путешествия
    // ------------------------------------------------------

    'travels' => [
        [
            'type' => FeatureType::SELECT,
            'name' => 'Сервис по путешествиям',
            'options' => [
                'yandex_travel' => 'Яндекс Путешествия',
                'ostrovok' => 'Островок',
                'tutu' => 'Туту.ру'
            ]
        ]
    ],
];