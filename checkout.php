<?php
$pageTitle = 'Оформление заказа - Автосервис';
$currentPage = 'cart';

require_once __DIR__ . '/includes/header.php';

use App\Cart;
use App\Order;
use App\PartsCatalog;

Cart::init();
$partsCatalog = new PartsCatalog();

$cartItems = Cart::getAll();
$cartTotal = Cart::getTotal();

// Если корзина пуста, перенаправляем
if (empty($cartItems)) {
    header('Location: cart.php');
    exit;
}

// Обработка формы заказа
$orderCreated = false;
$orderId = null;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

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

    // Проверка наличия товаров
    foreach ($cartItems as $item) {
        $part = $partsCatalog->getPartById($item['part_id']);
        if (!$part || $part['stock'] < $item['quantity']) {
            $errors[] = "Товар '{$item['part_name']}' недоступен в нужном количестве";
        }
    }

    if (empty($errors)) {
        try {
            // Подготовка данных заказа
            $orderData = [
                'name' => $name,
                'phone' => $phone,
                'email' => $email ?: null,
                'address' => $address ?: null,
                'total' => $cartTotal,
                'notes' => $notes ?: null
            ];

            // Подготовка позиций заказа
            $orderItems = [];
            foreach ($cartItems as $item) {
                $orderItems[] = [
                    'part_id' => $item['part_id'],
                    'service_id' => null,
                    'item_type' => 'part',
                    'part_name' => $item['part_name'],
                    'part_brand' => $item['part_brand'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity']
                ];
            }

            // Создание заказа
            $orderId = Order::create($orderData, $orderItems);
            
            // Очистка корзины
            Cart::clear();
            
            $orderCreated = true;
        } catch (\Exception $e) {
            $errors[] = 'Ошибка при создании заказа. Попробуйте позже.';
            error_log('Ошибка создания заказа: ' . $e->getMessage());
        }
    }
}
?>

<section class="container">
    <h1 style="text-align: center; margin-bottom: 2rem; color: var(--primary-color);">Оформление заказа</h1>

    <?php if ($orderCreated): ?>
        <div style="text-align: center; padding: 3rem; background: var(--bg-white); border-radius: 10px; box-shadow: var(--shadow);">
            <div style="font-size: 4rem; margin-bottom: 1rem;">✅</div>
            <h2 style="color: var(--primary-color); margin-bottom: 1rem;">Заказ успешно оформлен!</h2>
            <p style="font-size: 1.2rem; color: #666; margin-bottom: 2rem;">
                Номер вашего заказа: <strong>#<?php echo $orderId; ?></strong>
            </p>
            <p style="color: #666; margin-bottom: 2rem;">
                Мы свяжемся с вами в ближайшее время для подтверждения заказа.
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="order.php?id=<?php echo $orderId; ?>" class="btn btn-primary">Посмотреть заказ</a>
                <a href="orders.php" class="btn btn-outline">Все заказы</a>
                <a href="catalog.php" class="btn btn-outline">Вернуться в каталог</a>
            </div>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;">
            <!-- Форма заказа -->
            <div style="background: var(--bg-white); border-radius: 10px; padding: 2rem; box-shadow: var(--shadow);">
                <h2 style="color: var(--primary-color); margin-bottom: 1.5rem;">Контактная информация</h2>

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
                        <label for="address">Адрес доставки</label>
                        <textarea id="address" name="address" rows="3"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="notes">Комментарий к заказу</label>
                        <textarea id="notes" name="notes" rows="3"><?php echo htmlspecialchars($_POST['notes'] ?? ''); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Оформить заказ</button>
                </form>
            </div>

            <!-- Сводка заказа -->
            <div style="background: var(--bg-white); border-radius: 10px; padding: 2rem; box-shadow: var(--shadow);">
                <h2 style="color: var(--primary-color); margin-bottom: 1.5rem;">Ваш заказ</h2>
                
                <div style="display: grid; gap: 1rem; margin-bottom: 2rem;">
                    <?php foreach ($cartItems as $item): ?>
                        <div style="display: flex; justify-content: space-between; padding: 1rem; background: var(--bg-light); border-radius: 5px;">
                            <div>
                                <strong><?php echo htmlspecialchars($item['part_name']); ?></strong>
                                <p style="margin: 0.25rem 0 0 0; color: #666; font-size: 0.9rem;">
                                    <?php echo $item['quantity']; ?> × <?php echo number_format($item['price'], 0, ',', ' '); ?> ₸
                                </p>
                            </div>
                            <div style="font-weight: bold; color: var(--secondary-color);">
                                <?php echo number_format($item['price'] * $item['quantity'], 0, ',', ' '); ?> ₸
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div style="border-top: 2px solid var(--border-color); padding-top: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <strong style="font-size: 1.2rem;">Итого:</strong>
                        <strong style="font-size: 1.5rem; color: var(--secondary-color);">
                            <?php echo number_format($cartTotal, 0, ',', ' '); ?> ₸
                        </strong>
                    </div>
                </div>

                <div style="margin-top: 2rem;">
                    <a href="cart.php" class="btn btn-outline" style="width: 100%; text-align: center; text-decoration: none; display: block;">
                        Вернуться в корзину
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

