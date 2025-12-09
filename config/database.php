<?php

/**
 * Конфигурация базы данных
 * 
 * Поддерживает:
 * - Переменные окружения (DB_HOST, DB_PORT, etc.)
 * - DATABASE_URL (для Render.com и других платформ)
 * - Локальные значения по умолчанию
 */

// Если есть DATABASE_URL (Render.com предоставляет его)
if (isset($_ENV['DATABASE_URL'])) {
    $url = parse_url($_ENV['DATABASE_URL']);
    return [
        'host' => $url['host'] ?? 'localhost',
        'port' => $url['port'] ?? 3306,
        'database' => ltrim($url['path'] ?? '/autoservice', '/'),
        'username' => $url['user'] ?? 'root',
        'password' => $url['pass'] ?? '',
        'charset' => 'utf8mb4',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ];
}

// Используем отдельные переменные окружения
return [
    'host' => $_ENV['DB_HOST'] ?? $_ENV['MYSQL_HOST'] ?? 'localhost',
    'port' => (int)($_ENV['DB_PORT'] ?? $_ENV['MYSQL_PORT'] ?? 3306),
    'database' => $_ENV['DB_DATABASE'] ?? $_ENV['MYSQL_DATABASE'] ?? 'autoservice',
    'username' => $_ENV['DB_USERNAME'] ?? $_ENV['MYSQL_USER'] ?? 'root',
    'password' => $_ENV['DB_PASSWORD'] ?? $_ENV['MYSQL_PASSWORD'] ?? '',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];

