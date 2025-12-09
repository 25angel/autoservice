-- Создание базы данных для автосервиса
CREATE DATABASE IF NOT EXISTS autoservice CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE autoservice;

-- Таблица категорий запчастей
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица запчастей
CREATE TABLE IF NOT EXISTS parts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    brand VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    description TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    INDEX idx_category (category_id),
    INDEX idx_brand (brand),
    INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица услуг
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    duration VARCHAR(50),
    icon VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Вставка категорий
INSERT INTO categories (name) VALUES
('Тормозная система'),
('Двигатель'),
('Электрика'),
('Подвеска')
ON DUPLICATE KEY UPDATE name=name;

-- Вставка запчастей
INSERT INTO parts (name, category_id, brand, price, stock, description, image) VALUES
('Тормозные колодки передние', 1, 'Brembo', 18000.00, 15, 'Высококачественные тормозные колодки для передних колес', 'brake-pads.jpg'),
('Масляный фильтр', 2, 'Mann Filter', 3500.00, 45, 'Оригинальный масляный фильтр для всех типов двигателей', 'oil-filter.jpg'),
('Воздушный фильтр', 2, 'Bosch', 6000.00, 30, 'Воздушный фильтр премиум класса', 'air-filter.jpg'),
('Аккумулятор 60Ah', 3, 'Varta', 35000.00, 8, 'Аккумуляторная батарея 60Ah, 12V', 'battery.jpg'),
('Свечи зажигания', 2, 'NGK', 12000.00, 25, 'Комплект свечей зажигания (4 шт)', 'spark-plugs.jpg'),
('Амортизатор передний', 4, 'Monroe', 28000.00, 12, 'Амортизатор передний, пара', 'shock-absorber.jpg'),
('Ремень ГРМ', 2, 'Gates', 20000.00, 18, 'Ремень газораспределительного механизма', 'timing-belt.jpg'),
('Тормозной диск', 1, 'Brembo', 28000.00, 10, 'Тормозной диск передний, пара', 'brake-disc.jpg'),
('Стартер', 3, 'Bosch', 65000.00, 5, 'Стартер оригинальный', 'starter.jpg'),
('Стойка стабилизатора', 4, 'Lemforder', 12000.00, 20, 'Стойка стабилизатора поперечной устойчивости', 'stabilizer-link.jpg')
ON DUPLICATE KEY UPDATE name=name;

-- Вставка услуг
INSERT INTO services (name, description, price, duration, icon) VALUES
('Диагностика автомобиля', 'Комплексная компьютерная диагностика всех систем автомобиля', 6000.00, '1-2 часа', '🔍'),
('Замена масла и фильтров', 'Замена моторного масла, масляного и воздушного фильтров', 4000.00, '30-40 минут', '🛢️'),
('Ремонт тормозной системы', 'Замена колодок, дисков, суппортов. Прокачка тормозов', 12000.00, '2-3 часа', '🛑'),
('Ремонт подвески', 'Замена амортизаторов, стоек, сайлентблоков, пружин', 15000.00, '3-4 часа', '⚙️'),
('Ремонт двигателя', 'Капитальный и текущий ремонт двигателя любой сложности', 80000.00, '1-3 дня', '🔧'),
('Ремонт АКПП', 'Диагностика, ремонт и обслуживание автоматических коробок передач', 120000.00, '2-5 дней', '⚡'),
('Шиномонтаж', 'Балансировка, замена шин, ремонт проколов', 3000.00, '30 минут', '🛞'),
('Ремонт электрики', 'Диагностика и ремонт электрооборудования автомобиля', 8000.00, '2-4 часа', '⚡')
ON DUPLICATE KEY UPDATE name=name;

