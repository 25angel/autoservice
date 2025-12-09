-- Обновление цен на запчасти до реалистичных значений в тенге
USE autoservice;

-- Обновление цен на запчасти
UPDATE parts SET price = 18000.00 WHERE id = 1; -- Тормозные колодки передние
UPDATE parts SET price = 3500.00 WHERE id = 2; -- Масляный фильтр
UPDATE parts SET price = 6000.00 WHERE id = 3; -- Воздушный фильтр
UPDATE parts SET price = 35000.00 WHERE id = 4; -- Аккумулятор 60Ah
UPDATE parts SET price = 12000.00 WHERE id = 5; -- Свечи зажигания
UPDATE parts SET price = 28000.00 WHERE id = 6; -- Амортизатор передний
UPDATE parts SET price = 20000.00 WHERE id = 7; -- Ремень ГРМ
UPDATE parts SET price = 28000.00 WHERE id = 8; -- Тормозной диск
UPDATE parts SET price = 65000.00 WHERE id = 9; -- Стартер
UPDATE parts SET price = 12000.00 WHERE id = 10; -- Стойка стабилизатора

-- Обновление цен на услуги
UPDATE services SET price = 6000.00 WHERE id = 1; -- Диагностика автомобиля
UPDATE services SET price = 4000.00 WHERE id = 2; -- Замена масла и фильтров
UPDATE services SET price = 12000.00 WHERE id = 3; -- Ремонт тормозной системы
UPDATE services SET price = 15000.00 WHERE id = 4; -- Ремонт подвески
UPDATE services SET price = 80000.00 WHERE id = 5; -- Ремонт двигателя
UPDATE services SET price = 120000.00 WHERE id = 6; -- Ремонт АКПП
UPDATE services SET price = 3000.00 WHERE id = 7; -- Шиномонтаж
UPDATE services SET price = 8000.00 WHERE id = 8; -- Ремонт электрики
