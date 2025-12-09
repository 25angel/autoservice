-- Обновление таблицы order_items для поддержки услуг

USE autoservice;

-- Добавляем поля для услуг
ALTER TABLE order_items 
ADD COLUMN service_id INT NULL AFTER part_id,
ADD COLUMN item_type ENUM('part', 'service') DEFAULT 'part' AFTER service_id,
MODIFY COLUMN part_id INT NULL,
MODIFY COLUMN part_brand VARCHAR(100) NULL,
MODIFY COLUMN part_name VARCHAR(255) NULL;

-- Добавляем внешний ключ для услуг
ALTER TABLE order_items
ADD FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE RESTRICT;

-- Добавляем индекс
ALTER TABLE order_items
ADD INDEX idx_service (service_id);

