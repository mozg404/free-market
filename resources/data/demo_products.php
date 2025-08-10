<?php

return [

    // ------------------------------------------------------
    // Игры
    // ------------------------------------------------------

    'games' => [
        'name_modifiers' => [
            'Полная версия',
            'Все DLC',
            'Коллекционное издание',
            'Ранний доступ',
            'Закрытая бета',
            'С премиум-доступом',
            'С бонусным контентом',
        ],
        'list' => [
            [
                'name' => 'Death Stranding',
                'image' => resource_path('demo/products_images/games/death-stranding-directors.jpg'),
            ],
            [
                'name' => 'SpongeBob SquarePants: Titans of the Tide',
                'image' => resource_path('demo/products_images/games/death-stranding-directors.jpg'),
            ],
            [
                'name' => 'Detroit: Become Human',
                'image' => resource_path('demo/products_images/games/detroit-become-human.jpg'),
            ],
            [
                'name' => 'No Mans Sky',
                'image' => resource_path('demo/products_images/games/no-mans-sky.jpg'),
            ],
            [
                'name' => 'BioShock The Collection',
                'image' => resource_path('demo/products_images/games/bioshock-the-collection.jpg'),
            ],
            [
                'name' => 'Werewolf: The Apocalypse - Earthblood',
                'image' => resource_path('demo/products_images/games/werewolf-the-apocalypse-earthblood-steam.jpg'),
            ],
            [
                'name' => 'Beyond: Two Souls',
                'image' => resource_path('demo/products_images/games/beyond-two-souls.jpg'),
            ],
            [
                'name' => 'Resident Evil Village',
                'image' => resource_path('demo/products_images/games/resident-evil-village.jpg'),
            ],
            [
                'name' => 'Steelrising - Bastille Edition',
                'image' => resource_path('demo/products_images/games/steelrising-bastille-edition.jpg'),
            ]
        ],
    ],

    // ------------------------------------------------------
    // Игровые платформы и подписки
    // ------------------------------------------------------

    'game_services' => [
        'name_modifiers' => [
            'Навсегда',
            'Годовая подписка',
            'Премиум-аккаунт',
            'С игровой библиотекой',
            'С эксклюзивным контентом',
            'Для всех платформ',
            'С кешбэком'
        ],
        'list' => [
            [
                'name' => ['Epic Games Store', 'Epic Games', 'EGS'],
                'image' => resource_path('demo/products_images/game_services/epic-games.jpg'),
            ],
            [
                'name' => 'Xbox подписка',
                'image' => resource_path('demo/products_images/game_services/xbox.jpg'),
            ],
            [
                'name' => ['PS Networks', 'PlayStation Network', 'Play Station'],
                'image' => resource_path('demo/products_images/game_services/ps_network.jpg'),
            ],
            [
                'name' => 'Steam',
                'image' => resource_path('demo/products_images/game_services/steam.jpg'),
            ],
        ],
    ],

    // ------------------------------------------------------
    // Операционные системы
    // ------------------------------------------------------

    'os' => [
        'name_modifiers' => [
            'Лицензионный ключ',
            'Пожизненная активация',
            'Обновления до новых версий',
            'Для 5 устройств',
            'С технической поддержкой',
            'Корпоративная версия',
            'Мультиязычная'
        ],
        'list' => [
            [
                'name' => ['Windows 11 Pro', 'Windows 11 Профессиональная'],
                'image' => resource_path('demo/products_images/os/windows-11-pro.jpg'),
            ],
            [
                'name' => ['Windows 11 Home', 'Windows 11 Домашняя'],
                'image' => resource_path('demo/products_images/os/windows-11-home.jpg'),
            ],
            [
                'name' => 'Windows 10 LTSC 2021',
                'image' => resource_path('demo/products_images/os/windows-10-ltsc-2021.jpg'),
            ],
            [
                'name' => ['Windows 10 Home', 'Windows 10 Домашняя'],
                'image' => resource_path('demo/products_images/os/windows-10-home.jpg'),
            ],
            [
                'name' => ['Windows 7 Ultimate', 'Windows 7 Максимальная'],
                'image' => resource_path('demo/products_images/os/windows-7-ultimate.jpg'),
            ],
            [
                'name' => ['Windows 7 Pro', 'Windows 7 Профессиональная'],
                'image' => resource_path('demo/products_images/os/windows-7-professional.jpg'),
            ],
            [
                'name' => ['Windows 7 Home Basic', 'Windows 7 Домашняя Базовая'],
                'image' => resource_path('demo/products_images/os/windows-7-home-basic.jpg'),
            ],
            [
                'name' => ['Windows 7 Home Premium', 'Windows 7 Домашняя Премиум'],
                'image' => resource_path('demo/products_images/os/windows-7-home-premium.jpg'),
            ],
            [
                'name' => 'Windows Server 2022 Datacenter',
                'image' => resource_path('demo/products_images/os/windows-server-2022-datacenter.jpg'),
            ],
            [
                'name' => 'Windows Server 2025 Datacenter',
                'image' => resource_path('demo/products_images/os/windows-server-2025-datacenter.jpg'),
            ],
            [
                'name' => 'Windows Server 2025 Device Cal',
                'image' => resource_path('demo/products_images/os/windows-server-2025-device-cal.jpg'),
            ],
            [
                'name' => 'Windows Server 2025 User Cal',
                'image' => resource_path('demo/products_images/os/windows-server-2025-user-cal.jpg'),
            ],
            [
                'name' => 'Windows Server 2025 Standard',
                'image' => resource_path('demo/products_images/os/windows-server-2025-standard-1.jpg'),
            ],
        ],
    ],

    // ------------------------------------------------------
    // ИИ-сервисы
    // ------------------------------------------------------

    'ai' => [
        'name_modifiers' => [
            'PRO-версия',
            'С расширенными лимитами',
            'С обучением модели',
            'Без ограничений',
            'С API-доступом',
            'С премиум-функциями',
            'С технической поддержкой'
        ],
        'list' => [
            [
                'name' => 'ChatGRP 5 Plus',
                'image' => resource_path('demo/products_images/ai/chatgpt-5-plus.jpg'),
            ],
            [
                'name' => 'ChatGRP подписка',
                'image' => resource_path('demo/products_images/ai/chat-gpt.jpg'),
            ],
            [
                'name' => 'Grok AI',
                'image' => resource_path('demo/products_images/ai/grok-ai.jpg'),
            ],
        ],
    ],

    // ------------------------------------------------------
    // Медиа и стриминг
    // ------------------------------------------------------

    'media' => [
        'name_modifiers' => [
            'Годовая подписка',
            'Без рекламы',
            '4K/HDR',
            'С семейным доступом',
            'С оффлайн-режимом',
            'С эксклюзивным контентом',
            'Для всех устройств'
        ],
        'list' => [
            [
                'name' => 'Netflix',
                'image' => resource_path('demo/products_images/media/Netflix.jpg'),
            ],

            [
                'name' => 'Spotify',
                'image' => resource_path('demo/products_images/media/Spotify.jpg'),
            ],

            [
                'name' => 'Youtube',
                'image' => resource_path('demo/products_images/media/youtube.jpg'),
            ],
        ],
    ],

    // ------------------------------------------------------
    // Соцсети и коммуникации
    // ------------------------------------------------------

    'social' => [
        'name_modifiers' => [
            'Премиум-аккаунт',
            'Верифицированный',
            'С расширенной статистикой',
            'Без ограничений',
            'С бонусными функциями',
            'С защитой данных',
            'С увеличенными лимитами'
        ],
        'list' => [
            [
                'name' => 'Discord',
                'image' => resource_path('demo/products_images/social/discord.jpg'),
            ],
        ],
    ],

    // ------------------------------------------------------
    // Программное обеспечение
    // ------------------------------------------------------

    'work_soft' => [
        'name_modifiers' => [
            'PRO-версия',
            'Пожизненная лицензия',
            'Для коммерческого использования',
            'С облачным хранилищем',
            'С технической поддержкой',
            'Мультиязычная версия',
            'С обучающими материалами'
        ],
        'list' => [
            [
                'name' => 'Autodesk 3ds Max 2023',
                'image' => resource_path('demo/products_images/work_soft/3ds-max-2023.jpg'),
            ],
            [
                'name' => 'Autodesk 3ds Max 2024',
                'image' => resource_path('demo/products_images/work_soft/3ds-max-2024.jpg'),
            ],
            [
                'name' => 'Autodesk 3ds Max 2025',
                'image' => resource_path('demo/products_images/work_soft/3ds-max-2025.jpg'),
            ],
            [
                'name' => 'Adobe Acrobat Standard',
                'image' => resource_path('demo/products_images/work_soft/acrobat-standard.jpg'),
            ],
            [
                'name' => 'Adobe Creative Cloud',
                'image' => resource_path('demo/products_images/work_soft/adobe-creative-cloud.jpg'),
            ],
            [
                'name' => 'Adobe Substance 3D',
                'image' => resource_path('demo/products_images/work_soft/adobe-substance-3d.jpg'),
            ],
            [
                'name' => 'Autocad 2024',
                'image' => resource_path('demo/products_images/work_soft/autocad-2024.jpg'),
            ],
            [
                'name' => 'Autocad 2025',
                'image' => resource_path('demo/products_images/work_soft/autocad-2025.jpg'),
            ],
            [
                'name' => 'Autocad 2026',
                'image' => resource_path('demo/products_images/work_soft/autocad-2026.jpg'),
            ],
            [
                'name' => 'IntelliJ IDEA Ultimate',
                'image' => resource_path('demo/products_images/work_soft/intellij-idea-ultimate.jpg'),
            ],
            [
                'name' => ['Jetbrains ALL', 'Jetbrains Все'],
                'image' => resource_path('demo/products_images/work_soft/jetbrains-all-apps.jpg'),
            ],
            [
                'name' => 'Maya 2022',
                'image' => resource_path('demo/products_images/work_soft/maya-2022.jpg'),
            ],
            [
                'name' => 'Maya 2025',
                'image' => resource_path('demo/products_images/work_soft/maya-2025.jpg'),
            ],
            [
                'name' => 'Microsoft Access 2019',
                'image' => resource_path('demo/products_images/work_soft/microsoft-access-2019.jpg'),
            ],
            [
                'name' => 'Microsoft Access 2021',
                'image' => resource_path('demo/products_images/work_soft/microsoft-access-2021.jpg'),
            ],
            [
                'name' => 'Microsoft Access 2024',
                'image' => resource_path('demo/products_images/work_soft/microsoft-access-2024.jpg'),
            ],
            [
                'name' => 'Microsoft Outlook 2024',
                'image' => resource_path('demo/products_images/work_soft/microsoft-outlook-2024.jpg'),
            ],
            [
                'name' => 'Microsoft Powerpoint 2024',
                'image' => resource_path('demo/products_images/work_soft/microsoft-powerpoint-2024.jpg'),
            ],
            [
                'name' => 'Microsoft Project Professional 2019',
                'image' => resource_path('demo/products_images/work_soft/microsoft-project-professional-2019.jpg'),
            ],
            [
                'name' => 'Microsoft Project Professional 2021',
                'image' => resource_path('demo/products_images/work_soft/microsoft-project-professional-2021.jpg'),
            ],
            [
                'name' => 'Microsoft Project Standard 2024',
                'image' => resource_path('demo/products_images/work_soft/microsoft-project-standard-2024-scaled.jpg'),
            ],
            [
                'name' => 'Microsoft Visio Standard 2024',
                'image' => resource_path('demo/products_images/work_soft/microsoft-visio-standard-2024.jpg'),
            ],
            [
                'name' => 'Microsoft Visio Professional 2024',
                'image' => resource_path('demo/products_images/work_soft/microsoft-visio-professional-2024-scaled.jpg'),
            ],
            [
                'name' => 'Microsoft Word 2024',
                'image' => resource_path('demo/products_images/work_soft/microsoft-word-2024.jpg'),
            ],
            [
                'name' => 'Microsoft Excel 2024',
                'image' => resource_path('demo/products_images/work_soft/office-excel-2024.jpg'),
            ],
            [
                'name' => 'PHPStorm',
                'image' => resource_path('demo/products_images/work_soft/phpstorm.jpg'),
            ],
            [
                'name' => 'Pycharm',
                'image' => resource_path('demo/products_images/work_soft/pycharm.jpg'),
            ],
            [
                'name' => 'Revit 2024',
                'image' => resource_path('demo/products_images/work_soft/revit-2024.jpg'),
            ],
            [
                'name' => 'Revit 2025',
                'image' => resource_path('demo/products_images/work_soft/revit-2025.jpg'),
            ],
            [
                'name' => 'Visual Studio Enterprise 2022',
                'image' => resource_path('demo/products_images/work_soft/visual-studio-enterprice-2022.jpg'),
            ],
            [
                'name' => 'Visual Studio Enterprise 2019',
                'image' => resource_path('demo/products_images/work_soft/visual-studio-enterprise-2019.jpg'),
            ],
            [
                'name' => 'Visual Studio Professional 2019',
                'image' => resource_path('demo/products_images/work_soft/visual-studio-professional-2019.jpg'),
            ],
            [
                'name' => 'Visual Studio Professional 2022',
                'image' => resource_path('demo/products_images/work_soft/visual-studio-professional-2022-1.jpg'),
            ],
            [
                'name' => 'Webstorm',
                'image' => resource_path('demo/products_images/work_soft/webstorm.jpg'),
            ],
        ],
    ],

    // ------------------------------------------------------
    // VPN и безопасность
    // ------------------------------------------------------

    'vpn_security' => [
        'name_modifiers' => [
            'Годовая подписка',
            'Без логов',
            'С защитой от утечек',
            'Для 10 устройств',
            'С выделенным IP',
            'С максимальной скоростью',
            'С крипто-платежами'
        ],
        'list' => [
            [
                'name' => 'Dr.Web Security Space',
                'image' => resource_path('demo/products_images/vpn_security/drweb-security-space-2.jpg'),
            ],
            [
                'name' => 'Dr.Web Security Space Mobile',
                'image' => resource_path('demo/products_images/vpn_security/drweb-security-space-mobile.jpg'),
            ],
            [
                'name' => 'ESET NOD32 Internet Security',
                'image' => resource_path('demo/products_images/vpn_security/eset-nod32-internet-security.jpg'),
            ],
            [
                'name' => 'ESET NOD32 Mobile Security',
                'image' => resource_path('demo/products_images/vpn_security/eset-nod32-mobile-security.jpg'),
            ],
            [
                'name' => 'Kaspersky антивирус',
                'image' => resource_path('demo/products_images/vpn_security/kasperky-antivirus.jpg'),
            ],
            [
                'name' => 'Kaspersky Internet Security',
                'image' => resource_path('demo/products_images/vpn_security/kasperky-internet-security.jpg'),
            ],
            [
                'name' => 'Kaspersky Total Security',
                'image' => resource_path('demo/products_images/vpn_security/kasperky-total-security.jpg'),
            ],
            [
                'name' => 'Kaspersky Plus',
                'image' => resource_path('demo/products_images/vpn_security/kaspersky-plus.jpg'),
            ],
            [
                'name' => 'Kaspersky Premium',
                'image' => resource_path('demo/products_images/vpn_security/kaspersky-premium.jpg'),
            ],
            [
                'name' => 'Kaspersky Standard',
                'image' => resource_path('demo/products_images/vpn_security/kaspersky-standard.jpg'),
            ],
            [
                'name' => 'PRO32 Ultimate Security',
                'image' => resource_path('demo/products_images/vpn_security/pro32-ultimate_security.jpg'),
            ],
        ],
    ],

    // ------------------------------------------------------
    // Магазины и маркетплейсы
    // ------------------------------------------------------

    'shops' => [
        'name_modifiers' => [
            'Подарочная карта',
            'С кешбэком',
            'С бонусными баллами',
            'Без комиссии',
            'С расширенной гарантией',
            'С эксклюзивными предложениями',
            'С бесплатной доставкой',
        ],
        'list' => [
            [
                'name' => 'Яндекс.Афиша - сертификат на 2000руб',
                'image' => resource_path('demo/products_images/shops/yandex-apfisha-2000.webp'),
            ],
            [
                'name' => 'Sokolov сертификат',
                'image' => resource_path('demo/products_images/shops/sokolov-card.webp'),
            ],
            [
                'name' => 'kari сертификат',
                'image' => resource_path('demo/products_images/shops/kari-gift.webp'),
            ],
            [
                'name' => 'Kassir.ru подарочный сертификат',
                'image' => resource_path('demo/products_images/shops/kassir-sert.webp'),
            ],
        ],
    ],
];