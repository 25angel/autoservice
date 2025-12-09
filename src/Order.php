<?php

namespace App;

/**
 * Класс для работы с заказами
 */
class Order
{
    /**
     * Создать новый заказ
     */
    public static function create(array $orderData, array $items): int
    {
        try {
            Database::getConnection()->beginTransaction();

            // Создаем заказ
            $sql = "INSERT INTO orders (customer_name, customer_phone, customer_email, customer_address, total_amount, notes, status)
                    VALUES (:name, :phone, :email, :address, :total, :notes, 'new')";
            
            Database::execute($sql, [
                'name' => $orderData['name'],
                'phone' => $orderData['phone'],
                'email' => $orderData['email'] ?? null,
                'address' => $orderData['address'] ?? null,
                'total' => $orderData['total'],
                'notes' => $orderData['notes'] ?? null
            ]);

            $orderId = (int)Database::lastInsertId();

            // Добавляем позиции заказа
            foreach ($items as $item) {
                $itemType = $item['item_type'] ?? 'part';
                $partId = $item['part_id'] ?? null;
                $serviceId = $item['service_id'] ?? null;
                
                $sql = "INSERT INTO order_items (order_id, part_id, service_id, item_type, part_name, part_brand, quantity, price, subtotal)
                        VALUES (:order_id, :part_id, :service_id, :item_type, :part_name, :part_brand, :quantity, :price, :subtotal)";
                
                Database::execute($sql, [
                    'order_id' => $orderId,
                    'part_id' => $partId,
                    'service_id' => $serviceId,
                    'item_type' => $itemType,
                    'part_name' => $item['part_name'],
                    'part_brand' => $item['part_brand'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal']
                ]);

                // Уменьшаем количество на складе только для запчастей
                if ($itemType === 'part' && $partId) {
                    $sql = "UPDATE parts SET stock = stock - :quantity WHERE id = :part_id";
                    Database::execute($sql, [
                        'quantity' => $item['quantity'],
                        'part_id' => $partId
                    ]);
                }
            }

            Database::getConnection()->commit();
            return $orderId;
        } catch (\Exception $e) {
            Database::getConnection()->rollBack();
            error_log('Ошибка создания заказа: ' . $e->getMessage());
            throw new \RuntimeException('Не удалось создать заказ: ' . $e->getMessage());
        }
    }

    /**
     * Получить заказ по ID
     */
    public static function getById(int $id): ?array
    {
        try {
            $sql = "SELECT * FROM orders WHERE id = :id";
            $order = Database::queryOne($sql, ['id' => $id]);
            
            if ($order) {
                $order['items'] = self::getOrderItems($id);
            }
            
            return $order;
        } catch (\Exception $e) {
            error_log('Ошибка получения заказа: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Получить позиции заказа
     */
    public static function getOrderItems(int $orderId): array
    {
        try {
            $sql = "SELECT * FROM order_items WHERE order_id = :order_id";
            return Database::query($sql, ['order_id' => $orderId]);
        } catch (\Exception $e) {
            error_log('Ошибка получения позиций заказа: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Получить все заказы
     */
    public static function getAll(): array
    {
        try {
            $sql = "SELECT * FROM orders ORDER BY created_at DESC";
            return Database::query($sql);
        } catch (\Exception $e) {
            error_log('Ошибка получения заказов: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Обновить статус заказа
     */
    public static function updateStatus(int $orderId, string $status): bool
    {
        try {
            $sql = "UPDATE orders SET status = :status WHERE id = :id";
            return Database::execute($sql, ['status' => $status, 'id' => $orderId]);
        } catch (\Exception $e) {
            error_log('Ошибка обновления статуса заказа: ' . $e->getMessage());
            return false;
        }
    }
}

