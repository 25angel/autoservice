<?php
$pageTitle = 'Заказ услуги - Автосервис';
$currentPage = 'services';

require_once __DIR__ . '/includes/header.php';

use App\Order;

// Получение ID услуги
$serviceId = (int)($_POST['service_id'] ?? $_GET['service_id'] ?? 0);

if (!$serviceId) {
    header('Location: services.php');
    exit;
}

// Получение услуги
$service = $services->getServiceById($serviceId);

if (!$service) {
    header('Location: services.php');
    exit;
}

// Обработка формы заказа
$orderCreated = false;
$orderId = null;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    $preferredDate = trim($_POST['preferred_date'] ?? '');
    $preferredTime = trim($_POST['preferred_time'] ?? '');

    // Валидация
    if (empty($name)) {
        $errors[] = 'Укажите ваше имя';
    }
    if (empty($phone)) {
        $errors[] = 'Укажите ваш телефон';
    }
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Некорректный email адрес';
    }

    if (empty($errors)) {
        try {
            // Подготовка данных заказа
            $orderNotes = $service['name'] . "\n";
            if ($preferredDate) {
                $orderNotes .= "Желаемая дата: " . $preferredDate . "\n";
            }
            if ($preferredTime) {
                $orderNotes .= "Желаемое время: " . $preferredTime . "\n";
            }
            if ($notes) {
                $orderNotes .= "Комментарий: " . $notes;
            }

            $orderData = [
                'name' => $name,
                'phone' => $phone,
                'email' => $email ?: null,
                'address' => $address ?: null,
                'total' => $service['price'],
                'notes' => $orderNotes
            ];

            // Подготовка позиций заказа (услуга)
            $orderItems = [[
                'part_id' => null,
                'service_id' => $service['id'],
                'item_type' => 'service',
                'part_name' => $service['name'],
                'part_brand' => null,
                'quantity' => 1,
                'price' => $service['price'],
                'subtotal' => $service['price']
            ]];

            // Создание заказа
            $orderId = Order::create($orderData, $orderItems);
            
            $orderCreated = true;
        } catch (\Exception $e) {
            $errors[] = 'Ошибка при создании заказа. Попробуйте позже.';
            error_log('Ошибка создания заказа услуги: ' . $e->getMessage());
        }
    }
}
?>

<section class="container">
    <h1 style="text-align: center; margin-bottom: 2rem; color: var(--primary-color);">Заказ услуги</h1>

    <?php if ($orderCreated): ?>
        <div style="text-align: center; padding: 3rem; background: var(--bg-white); border-radius: 10px; box-shadow: var(--shadow);">
            <div style="font-size: 4rem; margin-bottom: 1rem;">✅</div>
            <h2 style="color: var(--primary-color); margin-bottom: 1rem;">Заявка успешно отправлена!</h2>
            <p style="font-size: 1.2rem; color: #666; margin-bottom: 2rem;">
                Номер вашей заявки: <strong>#<?php echo $orderId; ?></strong>
            </p>
            <p style="color: #666; margin-bottom: 2rem;">
                Мы свяжемся с вами в ближайшее время для подтверждения записи.
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="order.php?id=<?php echo $orderId; ?>" class="btn btn-primary">Посмотреть заявку</a>
                <a href="services.php" class="btn btn-outline">Вернуться к услугам</a>
            </div>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;">
            <!-- Форма заказа -->
            <div style="background: var(--bg-white); border-radius: 10px; padding: 2rem; box-shadow: var(--shadow);">
                <h2 style="color: var(--primary-color); margin-bottom: 1.5rem;">Контактная информация</h2>

                <!-- Информация об услуге -->
                <div style="background: #e3f2fd; padding: 1rem; border-radius: 5px; margin-bottom: 1.5rem;">
                    <strong><?php echo htmlspecialchars($service['name']); ?></strong>
                    <p style="margin: 0.5rem 0 0 0; color: #666;">
                        Цена: <?php echo number_format($service['price'], 0, ',', ' '); ?> ₸<br>
                        Время: <?php echo htmlspecialchars($service['duration']); ?>
                    </p>
                </div>

                <?php if (!empty($errors)): ?>
                    <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 5px; margin-bottom: 1.5rem;">
                        <strong>Ошибки:</strong>
                        <ul style="margin: 0.5rem 0 0 0; padding-left: 1.5rem;">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                    
                    <div class="form-group">
                        <label for="name">Имя *</label>
                        <input type="text" id="name" name="name" required 
                               value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="phone">Телефон *</label>
                        <input type="tel" id="phone" name="phone" required 
                               value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                               placeholder="+7 (999) 123-45-67">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" 
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="address">Адрес (если требуется выезд)</label>
                        <textarea id="address" name="address" rows="2"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="preferred_date">Желаемая дата</label>
                        <input type="date" id="preferred_date" name="preferred_date" 
                               value="<?php echo htmlspecialchars($_POST['preferred_date'] ?? ''); ?>"
                               min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="preferred_time">Желаемое время</label>
                        <input type="time" id="preferred_time" name="preferred_time" 
                               value="<?php echo htmlspecialchars($_POST['preferred_time'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="notes">Комментарий</label>
                        <textarea id="notes" name="notes" rows="3"><?php echo htmlspecialchars($_POST['notes'] ?? ''); ?></textarea>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary" style="width: 100%;">Отправить заявку</button>
                </form>
            </div>

            <!-- Информация об услуге -->
            <div style="background: var(--bg-white); border-radius: 10px; padding: 2rem; box-shadow: var(--shadow);">
                <div style="text-align: center; margin-bottom: 2rem;">
                    <div style="font-size: 4rem; margin-bottom: 1rem;"><?php echo $service['icon']; ?></div>
                    <h2 style="color: var(--primary-color); margin: 0;"><?php echo htmlspecialchars($service['name']); ?></h2>
                </div>

                <div style="background: var(--bg-light); padding: 1.5rem; border-radius: 5px; margin-bottom: 2rem;">
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Описание</h3>
                    <p style="line-height: 1.8; color: #666;"><?php echo nl2br(htmlspecialchars($service['description'])); ?></p>
                </div>

                <div style="background: #fff3cd; padding: 1.5rem; border-radius: 5px; margin-bottom: 2rem;">
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Информация</h3>
                    <p style="margin: 0.5rem 0;"><strong>Цена:</strong> от <?php echo number_format($service['price'], 0, ',', ' '); ?> ₸</p>
                    <p style="margin: 0.5rem 0;"><strong>Время выполнения:</strong> <?php echo htmlspecialchars($service['duration']); ?></p>
                </div>

                <div style="background: #d4edda; padding: 1.5rem; border-radius: 5px;">
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Гарантии</h3>
                    <ul style="line-height: 2; color: #666; margin: 0;">
                        <li>Гарантия на выполненные работы</li>
                        <li>Профессиональные мастера</li>
                        <li>Оригинальные запчасти</li>
                        <li>Быстрое выполнение</li>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

