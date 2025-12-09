<?php

namespace App;

use PDO;
use PDOException;

/**
 * Класс для работы с базой данных
 */
class Database
{
    private static ?PDO $connection = null;
    private static array $config = [];

    /**
     * Получить подключение к базе данных
     */
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            self::connect();
        }
        return self::$connection;
    }

    /**
     * Подключение к базе данных
     */
    private static function connect(): void
    {
        try {
            self::$config = require __DIR__ . '/../config/database.php';
            
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                self::$config['host'],
                self::$config['port'],
                self::$config['database'],
                self::$config['charset']
            );

            self::$connection = new PDO(
                $dsn,
                self::$config['username'],
                self::$config['password'],
                self::$config['options']
            );
            
            // Явно устанавливаем UTF-8 для подключения
            self::$connection->exec("SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'");
            self::$connection->exec("SET CHARACTER SET utf8mb4");
            self::$connection->exec("SET character_set_connection=utf8mb4");
        } catch (PDOException $e) {
            throw new \RuntimeException('Ошибка подключения к базе данных: ' . $e->getMessage());
        }
    }

    /**
     * Выполнить запрос и вернуть результат
     */
    public static function query(string $sql, array $params = []): array
    {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Выполнить запрос и вернуть одну запись
     */
    public static function queryOne(string $sql, array $params = []): ?array
    {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Выполнить запрос без возврата результата (INSERT, UPDATE, DELETE)
     */
    public static function execute(string $sql, array $params = []): bool
    {
        $stmt = self::getConnection()->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Получить последний вставленный ID
     */
    public static function lastInsertId(): string
    {
        return self::getConnection()->lastInsertId();
    }
}

