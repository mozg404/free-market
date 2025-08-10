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
        ]
    ],

    // ------------------------------------------------------
    // Операционные системы
    // ------------------------------------------------------

    'os' => [
        [
            'type' => FeatureType::SELECT,
            'name' => 'ОС',
            'options' => [
                'windows_11' => 'Windows 11',
                'windows_10' => 'Windows 10',
                'windows_server' => 'Windows Server',
                'macos' => 'macOS',
                'ubuntu' => 'Ubuntu',
                'rosa_linux' => 'ROSA Linux',
                'alt_linux' => 'ALT Linux'
            ]
        ]
    ],

    // ------------------------------------------------------
    // ИИ-сервисы
    // ------------------------------------------------------

    'ai' => [
        [
            'type' => FeatureType::SELECT,
            'name' => 'Нейросеть',
            'options' => [
                'chatgpt_plus' => 'ChatGPT Plus',
                'midjourney' => 'Midjourney',
                'yandex_gpt' => 'YandexGPT',
                'gigachat' => 'GigaChat',
                'claude' => 'Claude AI',
                'stable_diffusion' => 'Stable Diffusion',
                'dalle' => 'DALL-E',
                'kandinsky' => 'Kandinsky AI'
            ]
        ]
    ],

    // ------------------------------------------------------
    // Медиа и стриминг
    // ------------------------------------------------------

    'media' => [
        [
            'type' => FeatureType::SELECT,
            'name' => 'Медиа сервис',
            'options' => [
                'spotify' => 'Spotify',
                'youtube_premium' => 'YouTube Premium',
                'netflix' => 'Netflix',
                'disney_plus' => 'Disney+',
                'apple_music' => 'Apple Music',
                'twitch' => 'Twitch',
                'yandex_music' => 'Яндекс Музыка',
                'yandex_plus' => 'Яндекс Плюс',
                'ivi' => 'IVI',
                'okko' => 'Okko',
                'kinopoisk' => 'Кинопоиск'
            ]
        ]
    ],

    // ------------------------------------------------------
    // Соцсети и коммуникации
    // ------------------------------------------------------

    'social' => [
        [
            'type' => FeatureType::SELECT,
            'name' => 'Соц. сеть',
            'options' => [
                'discord' => 'Discord',
                'discord_nitro' => 'Discord Nitro',
                'telegram' => 'Telegram',
                'vkontakte' => 'ВКонтакте',
                'facebook' => 'Facebook',
                'instagram' => 'Instagram',
                'twitter' => 'Twitter',
                'tiktok' => 'TikTok',
            ]
        ]
    ],

    // ------------------------------------------------------
    // Программное обеспечение
    // ------------------------------------------------------

    'work_soft' => [
        [
            'type' => FeatureType::SELECT,
            'name' => 'Софт',
            'options' => [
                'adobe_photoshop' => 'Adobe Photoshop',
                'adobe_illustrator' => 'Adobe Illustrator',
                'microsoft_office' => 'Microsoft Office',
                'microsoft_365' => 'Microsoft 365',
                'notion' => 'Notion',
                'figma' => 'Figma',
                'phpstorm' => 'PhpStorm',
                'intellij_idea' => 'IntelliJ IDEA',
            ]
        ]
    ],

    // ------------------------------------------------------
    // VPN и безопасность
    // ------------------------------------------------------

    'vpn_security' => [
        [
            'type' => FeatureType::SELECT,
            'name' => 'VPN сервис',
            'options' => [
                'nordvpn' => 'NordVPN',
                'expressvpn' => 'ExpressVPN',
                'kaspersky' => 'Касперский',
                'drweb' => 'Dr.Web',
                'mcafee' => 'McAfee',
            ]
        ]
    ],
];