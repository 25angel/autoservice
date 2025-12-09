<?php

namespace App;

/**
 * Класс для работы с каталогом запчастей
 */
class PartsCatalog
{
    /**
     * Получить все запчасти
     */
    public function getAllParts(): array
    {
        try {
            $sql = "SELECT p.*, c.name as category 
                    FROM parts p 
                    JOIN categories c ON p.category_id = c.id 
                    ORDER BY p.name";
            $parts = Database::query($sql);
            
            // Преобразуем price из строки в число для совместимости
            return array_map(function($part) {
                $part['price'] = (float)$part['price'];
                $part['stock'] = (int)$part['stock'];
                $part['id'] = (int)$part['id'];
                return $part;
            }, $parts);
        } catch (\Exception $e) {
            // Если БД недоступна, возвращаем пустой массив
            error_log('Ошибка получения запчастей: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Получить запчасти по категории
     */
    public function getPartsByCategory(string $category): array
    {
        try {
            $sql = "SELECT p.*, c.name as category 
                    FROM parts p 
                    JOIN categories c ON p.category_id = c.id 
                    WHERE c.name = :category 
                    ORDER BY p.name";
            $parts = Database::query($sql, ['category' => $category]);
            
            return array_map(function($part) {
                $part['price'] = (float)$part['price'];
                $part['stock'] = (int)$part['stock'];
                $part['id'] = (int)$part['id'];
                return $part;
            }, $parts);
        } catch (\Exception $e) {
            error_log('Ошибка получения запчастей по категории: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Получить запчасть по ID
     */
    public function getPartById(int $id): ?array
    {
        try {
            $sql = "SELECT p.*, c.name as category 
                    FROM parts p 
                    JOIN categories c ON p.category_id = c.id 
                    WHERE p.id = :id";
            $part = Database::queryOne($sql, ['id' => $id]);
            
            if ($part) {
                $part['price'] = (float)$part['price'];
                $part['stock'] = (int)$part['stock'];
                $part['id'] = (int)$part['id'];
            }
            
            return $part;
        } catch (\Exception $e) {
            error_log('Ошибка получения запчасти: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Поиск запчастей
     */
    public function searchParts(string $query): array
    {
        try {
            $searchTerm = '%' . $query . '%';
            // База данных использует utf8mb4_unicode_ci, который нечувствителен к регистру
            // Но для надежности используем LOWER() для обеих сторон сравнения
            // Используем разные имена параметров для каждого условия
            $sql = "SELECT p.*, c.name as category 
                    FROM parts p 
                    JOIN categories c ON p.category_id = c.id 
                    WHERE LOWER(p.name) LIKE LOWER(:query_name) 
                       OR LOWER(p.brand) LIKE LOWER(:query_brand) 
                       OR LOWER(c.name) LIKE LOWER(:query_category) 
                    ORDER BY p.name";
            $parts = Database::query($sql, [
                'query_name' => $searchTerm,
                'query_brand' => $searchTerm,
                'query_category' => $searchTerm
            ]);
            
            return array_map(function($part) {
                $part['price'] = (float)$part['price'];
                $part['stock'] = (int)$part['stock'];
                $part['id'] = (int)$part['id'];
                return $part;
            }, $parts);
        } catch (\Exception $e) {
            error_log('Ошибка поиска запчастей: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Поиск запчастей с фильтром по категории
     * 
     * @param string $query Поисковый запрос
     * @param string|null $category Название категории (null для всех категорий)
     * @return array
     */
    public function searchPartsWithCategory(string $query = '', ?string $category = null): array
    {
        try {
            $conditions = [];
            $params = [];

            // Поиск по запросу (нечувствителен к регистру)
            if (!empty($query)) {
                $searchTerm = '%' . $query . '%';
                // База данных использует utf8mb4_unicode_ci, который нечувствителен к регистру
                // Но для надежности используем LOWER() для обеих сторон сравнения
                // Используем разные имена параметров для каждого условия
                $conditions[] = "(LOWER(p.name) LIKE LOWER(:query_name) 
                                  OR LOWER(p.brand) LIKE LOWER(:query_brand) 
                                  OR LOWER(c.name) LIKE LOWER(:query_category))";
                $params['query_name'] = $searchTerm;
                $params['query_brand'] = $searchTerm;
                $params['query_category'] = $searchTerm;
            }

            // Фильтр по категории
            if (!empty($category)) {
                $conditions[] = "c.name = :category";
                $params['category'] = $category;
            }

            // Формируем WHERE условие
            $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

            $sql = "SELECT p.*, c.name as category 
                    FROM parts p 
                    JOIN categories c ON p.category_id = c.id 
                    {$whereClause}
                    ORDER BY p.name";
            
            $parts = Database::query($sql, $params);
            
            return array_map(function($part) {
                $part['price'] = (float)$part['price'];
                $part['stock'] = (int)$part['stock'];
                $part['id'] = (int)$part['id'];
                return $part;
            }, $parts);
        } catch (\Exception $e) {
            error_log('Ошибка поиска запчастей с фильтром: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Получить все категории
     */
    public function getCategories(): array
    {
        try {
            $sql = "SELECT name FROM categories ORDER BY name";
            $categories = Database::query($sql);
            return array_column($categories, 'name');
        } catch (\Exception $e) {
            error_log('Ошибка получения категорий: ' . $e->getMessage());
            return [];
        }
    }
}
