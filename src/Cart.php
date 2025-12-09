<?php

namespace App;

/**
 * Класс для работы с корзиной (сессия)
 */
class Cart
{
    /**
     * Инициализация корзины
     */
    public static function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    /**
     * Добавить товар в корзину
     */
    public static function add(int $partId, int $quantity, array $partData): void
    {
        self::init();
        
        if (isset($_SESSION['cart'][$partId])) {
            $_SESSION['cart'][$partId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$partId] = [
                'part_id' => $partId,
                'part_name' => $partData['name'],
                'part_brand' => $partData['brand'],
                'price' => (float)$partData['price'],
                'quantity' => $quantity,
                'stock' => (int)$partData['stock']
            ];
        }
    }

    /**
     * Обновить количество товара
     */
    public static function update(int $partId, int $quantity): void
    {
        self::init();
        
        if (isset($_SESSION['cart'][$partId])) {
            if ($quantity <= 0) {
                self::remove($partId);
            } else {
                $_SESSION['cart'][$partId]['quantity'] = $quantity;
            }
        }
    }

    /**
     * Удалить товар из корзины
     */
    public static function remove(int $partId): void
    {
        self::init();
        unset($_SESSION['cart'][$partId]);
    }

    /**
     * Очистить корзину
     */
    public static function clear(): void
    {
        self::init();
        $_SESSION['cart'] = [];
    }

    /**
     * Получить все товары в корзине
     */
    public static function getAll(): array
    {
        self::init();
        return $_SESSION['cart'] ?? [];
    }

    /**
     * Получить количество товаров в корзине
     */
    public static function getCount(): int
    {
        self::init();
        $count = 0;
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }

    /**
     * Получить общую сумму корзины
     */
    public static function getTotal(): float
    {
        self::init();
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    /**
     * Проверить, есть ли товар в корзине
     */
    public static function has(int $partId): bool
    {
        self::init();
        return isset($_SESSION['cart'][$partId]);
    }
}

