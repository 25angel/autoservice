<?php

namespace App;

/**
 * Класс для работы с услугами автосервиса
 */
class Services
{
    /**
     * Получить все услуги
     */
    public function getAllServices(): array
    {
        try {
            $sql = "SELECT * FROM services ORDER BY name";
            $services = Database::query($sql);
            
            // Преобразуем price из строки в число для совместимости
            return array_map(function($service) {
                $service['price'] = (float)$service['price'];
                $service['id'] = (int)$service['id'];
                return $service;
            }, $services);
        } catch (\Exception $e) {
            // Если БД недоступна, возвращаем пустой массив
            error_log('Ошибка получения услуг: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Получить услугу по ID
     */
    public function getServiceById(int $id): ?array
    {
        try {
            $sql = "SELECT * FROM services WHERE id = :id";
            $service = Database::queryOne($sql, ['id' => $id]);
            
            if ($service) {
                $service['price'] = (float)$service['price'];
                $service['id'] = (int)$service['id'];
            }
            
            return $service;
        } catch (\Exception $e) {
            error_log('Ошибка получения услуги: ' . $e->getMessage());
            return null;
        }
    }
}
