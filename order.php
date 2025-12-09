<?php
$pageTitle = 'Заказ - Автосервис';
$currentPage = 'orders';

require_once __DIR__ . '/includes/header.php';

use App\Order;

// Получение ID заказа
$orderId = (int)($_GET['id'] ?? 0);

if (!$orderId) {
    header('Location: orders.php');
    exit;
}

// Получение заказа
$order = Order::getById($orderId);

if (!$order) {
    header('Location: orders.php');
    exit;
}

$orderItems = $order['items'] ?? [];
?>

<section class="container">
    <div style="margin-bottom: 1rem;">
        <a href="orders.php" style="color: var(--accent-color); text-decoration: none;">← Вернуться к списку заказов</a>
    </div>

    <div style="background: var(--bg-white); border-radius: 10px; padding: 2rem; box-shadow: var(--shadow); margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--primary-color); margin: 0 0 0.5rem 0;">
                    Заказ #<?php echo $order['id']; ?>
                </h1>
                <p style="color: #666; margin: 0;">
                    Дата: <?php echo date('d.m.Y H:i', strtotime($order['created_at'])); ?>
                </p>
            </div>
            <div style="text-align: right;">
                <?php
                $statusLabels = [
                    'new' => ['label' => 'Новый', 'class' => 'badge-success'],
                    'processing' => ['label' => 'В обработке', 'class' => 'badge-warning'],
                    'completed' => ['label' => 'Выполнен', 'class' => 'badge-success'],
                    'cancelled' => ['label' => 'Отменен', 'class' => 'badge-danger']
                ];
                $status = $statusLabels[$order['status']] ?? ['label' => $order['status'], 'class' => 'badge'];
                ?>
                <span class="badge <?php echo $status['class']; ?>" style="font-size: 1rem; padding: 0.5rem 1rem;">
                    <?php echo $status['label']; ?>
                </span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
            <div>
                <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Информация о клиенте</h3>
                <div style="background: var(--bg-light); padding: 1.5rem; border-radius: 5px;">
                    <p style="margin: 0.5rem 0;"><strong>Имя:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                    <p style="margin: 0.5rem 0;"><strong>Телефон:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
                    <?php if ($order['customer_email']): ?>
                        <p style="margin: 0.5rem 0;"><strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
                    <?php endif; ?>
                    <?php if ($order['customer_address']): ?>
                        <p style="margin: 0.5rem 0;"><strong>Адрес:</strong> <?php echo nl2br(htmlspecialchars($order['customer_address'])); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Дополнительная информация</h3>
                <div style="background: var(--bg-light); padding: 1.5rem; border-radius: 5px;">
                    <?php if ($order['notes']): ?>
                        <p style="margin: 0.5rem 0;"><strong>Комментарий:</strong></p>
                        <p style="margin: 0.5rem 0; color: #666;"><?php echo nl2br(htmlspecialchars($order['notes'])); ?></p>
                    <?php else: ?>
                        <p style="margin: 0.5rem 0; color: #666;">Комментариев нет</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div>
            <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Состав заказа</h3>
            <div style="background: var(--bg-light); border-radius: 5px; overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: var(--primary-color); color: white;">
                            <th style="padding: 1rem; text-align: left;">Товар</th>
                            <th style="padding: 1rem; text-align: center;">Количество</th>
                            <th style="padding: 1rem; text-align: right;">Цена</th>
                            <th style="padding: 1rem; text-align: right;">Сумма</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderItems as $index => $item): ?>
                            <tr style="border-bottom: 1px solid var(--border-color); <?php echo $index % 2 === 0 ? 'background: white;' : ''; ?>">
                                <td style="padding: 1rem;">
                                    <strong><?php echo htmlspecialchars($item['part_name']); ?></strong>
                                    <?php if ($item['item_type'] === 'service'): ?>
                                        <span class="badge badge-success" style="margin-left: 0.5rem;">Услуга</span>
                                    <?php elseif ($item['part_brand']): ?>
                                        <p style="margin: 0.25rem 0 0 0; color: #666; font-size: 0.9rem;">
                                            Бренд: <?php echo htmlspecialchars($item['part_brand']); ?>
                                        </p>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 1rem; text-align: center;">
                                    <?php echo $item['item_type'] === 'service' ? '1' : $item['quantity'] . ' шт.'; ?>
                                </td>
                                <td style="padding: 1rem; text-align: right;">
                                    <?php echo number_format($item['price'], 0, ',', ' '); ?> ₸
                                </td>
                                <td style="padding: 1rem; text-align: right; font-weight: bold;">
                                    <?php echo number_format($item['subtotal'], 0, ',', ' '); ?> ₸
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr style="background: var(--primary-color); color: white;">
                            <td colspan="3" style="padding: 1rem; text-align: right; font-weight: bold; font-size: 1.2rem;">
                                Итого:
                            </td>
                            <td style="padding: 1rem; text-align: right; font-weight: bold; font-size: 1.2rem;">
                                <?php echo number_format($order['total_amount'], 0, ',', ' '); ?> ₸
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

