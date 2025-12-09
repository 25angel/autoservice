<?php
$pageTitle = 'Заказы - Автосервис';
$currentPage = 'orders';

require_once __DIR__ . '/includes/header.php';

use App\Order;

// Получение всех заказов
$orders = Order::getAll();
?>

<section class="container">
    <h1 style="text-align: center; margin-bottom: 2rem; color: var(--primary-color);">Список заказов</h1>

    <?php if (empty($orders)): ?>
        <div style="text-align: center; padding: 3rem; background: var(--bg-white); border-radius: 10px; box-shadow: var(--shadow);">
            <div style="font-size: 4rem; margin-bottom: 1rem;">📦</div>
            <p style="font-size: 1.2rem; color: #666;">Заказов пока нет</p>
        </div>
    <?php else: ?>
        <div style="background: var(--bg-white); border-radius: 10px; padding: 2rem; box-shadow: var(--shadow);">
            <div style="display: grid; gap: 1rem;">
                <?php foreach ($orders as $order): ?>
                    <div style="border: 1px solid var(--border-color); border-radius: 10px; padding: 1.5rem; transition: box-shadow 0.3s;">
                        <div style="display: grid; grid-template-columns: auto 1fr auto; gap: 2rem; align-items: start;">
                            <!-- Левая колонка - номер заказа -->
                            <div>
                                <div style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color); margin-bottom: 0.5rem;">
                                    #<?php echo $order['id']; ?>
                                </div>
                                <div style="font-size: 0.9rem; color: #666;">
                                    <?php echo date('d.m.Y H:i', strtotime($order['created_at'])); ?>
                                </div>
                            </div>

                            <!-- Центральная колонка - информация о заказе -->
                            <div>
                                <?php
                                // Определяем тип заказа (запчасти или услуги)
                                $orderItems = Order::getOrderItems($order['id']);
                                $hasServices = false;
                                $hasParts = false;
                                foreach ($orderItems as $item) {
                                    if (($item['item_type'] ?? 'part') === 'service') {
                                        $hasServices = true;
                                    } else {
                                        $hasParts = true;
                                    }
                                }
                                $orderType = $hasServices && $hasParts ? 'Запчасти и услуги' : ($hasServices ? 'Услуги' : 'Запчасти');
                                ?>
                                <div style="margin-bottom: 0.5rem;">
                                    <strong>Тип:</strong> 
                                    <span class="badge <?php echo $hasServices ? 'badge-success' : 'badge-warning'; ?>">
                                        <?php echo $orderType; ?>
                                    </span>
                                </div>
                                <div style="margin-bottom: 0.5rem;">
                                    <strong>Клиент:</strong> <?php echo htmlspecialchars($order['customer_name']); ?>
                                </div>
                                <div style="margin-bottom: 0.5rem;">
                                    <strong>Телефон:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?>
                                </div>
                                <?php if ($order['customer_email']): ?>
                                    <div style="margin-bottom: 0.5rem;">
                                        <strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($order['customer_address']): ?>
                                    <div style="margin-bottom: 0.5rem;">
                                        <strong>Адрес:</strong> <?php echo htmlspecialchars($order['customer_address']); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($order['notes']): ?>
                                    <div style="margin-top: 0.5rem; padding: 0.75rem; background: var(--bg-light); border-radius: 5px;">
                                        <strong>Комментарий:</strong> <?php echo nl2br(htmlspecialchars($order['notes'])); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Правая колонка - сумма и статус -->
                            <div style="text-align: right;">
                                <div style="font-size: 1.5rem; font-weight: bold; color: var(--secondary-color); margin-bottom: 1rem;">
                                    <?php echo number_format($order['total_amount'], 0, ',', ' '); ?> ₸
                                </div>
                                <div style="margin-bottom: 1rem;">
                                    <?php
                                    $statusLabels = [
                                        'new' => ['label' => 'Новый', 'class' => 'badge-success'],
                                        'processing' => ['label' => 'В обработке', 'class' => 'badge-warning'],
                                        'completed' => ['label' => 'Выполнен', 'class' => 'badge-success'],
                                        'cancelled' => ['label' => 'Отменен', 'class' => 'badge-danger']
                                    ];
                                    $status = $statusLabels[$order['status']] ?? ['label' => $order['status'], 'class' => 'badge'];
                                    ?>
                                    <span class="badge <?php echo $status['class']; ?>">
                                        <?php echo $status['label']; ?>
                                    </span>
                                </div>
                                <a href="order.php?id=<?php echo $order['id']; ?>" class="btn btn-primary" style="text-decoration: none;">
                                    Подробнее
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

