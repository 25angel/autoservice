<?php
$pageTitle = 'Услуга - Автосервис';
$currentPage = 'services';

require_once __DIR__ . '/includes/header.php';

// Получение ID услуги
$serviceId = (int)($_GET['id'] ?? 0);

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

// Получение похожих услуг
$allServices = $services->getAllServices();
$relatedServices = array_filter($allServices, function($s) use ($serviceId) {
    return $s['id'] !== $serviceId;
});
$relatedServices = array_slice($relatedServices, 0, 4); // Показываем максимум 4
?>

<section class="container">
    <div style="margin-bottom: 1rem;">
        <a href="services.php" style="color: var(--accent-color); text-decoration: none;">← Вернуться к услугам</a>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-bottom: 3rem;">
        <!-- Левая колонка - иконка и информация -->
        <div>
            <div style="background: var(--bg-white); border-radius: 10px; padding: 3rem; box-shadow: var(--shadow); text-align: center;">
                <div style="font-size: 8rem; margin-bottom: 1rem;"><?php echo $service['icon']; ?></div>
                <h1 style="font-size: 2rem; color: var(--primary-color); margin: 1rem 0;"><?php echo htmlspecialchars($service['name']); ?></h1>
            </div>
        </div>

        <!-- Правая колонка - детали и заказ -->
        <div>
            <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px; margin-bottom: 2rem;">
                <div style="font-size: 2.5rem; color: var(--secondary-color); font-weight: bold; margin-bottom: 0.5rem;">
                    от <?php echo number_format($service['price'], 0, ',', ' '); ?> ₸
                </div>
                <p class="service-duration" style="margin: 0;">
                    ⏱️ Время выполнения: <?php echo htmlspecialchars($service['duration']); ?>
                </p>
            </div>

            <div style="margin: 2rem 0;">
                <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Описание услуги</h3>
                <p style="line-height: 1.8; color: #666; font-size: 1.1rem;"><?php echo nl2br(htmlspecialchars($service['description'])); ?></p>
            </div>

            <div style="background: #e3f2fd; padding: 1.5rem; border-radius: 10px; margin: 2rem 0;">
                <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Что входит в услугу:</h3>
                <ul style="line-height: 2; color: #666;">
                    <li>Профессиональная диагностика</li>
                    <li>Качественное выполнение работ</li>
                    <li>Гарантия на выполненные работы</li>
                    <li>Консультация специалиста</li>
                </ul>
            </div>

            <form method="POST" action="service-order.php" style="margin-top: 2rem;">
                <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1.1rem; padding: 1rem;">
                    Записаться на услугу
                </button>
            </form>
        </div>
    </div>

    <!-- Похожие услуги -->
    <?php if (!empty($relatedServices)): ?>
        <div style="margin-top: 4rem;">
            <h2 style="color: var(--primary-color); margin-bottom: 2rem;">Другие услуги</h2>
            <div class="services-grid">
                <?php foreach ($relatedServices as $relatedService): ?>
                    <div class="service-card">
                        <div class="service-icon"><?php echo $relatedService['icon']; ?></div>
                        <h3 class="card-title">
                            <a href="service.php?id=<?php echo $relatedService['id']; ?>" style="color: inherit; text-decoration: none;">
                                <?php echo htmlspecialchars($relatedService['name']); ?>
                            </a>
                        </h3>
                        <p class="card-description"><?php echo htmlspecialchars($relatedService['description']); ?></p>
                        <div class="card-price">от <?php echo number_format($relatedService['price'], 0, ',', ' '); ?> ₸</div>
                        <p class="service-duration">⏱️ <?php echo htmlspecialchars($relatedService['duration']); ?></p>
                        <div style="margin-top: 1.5rem;">
                            <a href="service.php?id=<?php echo $relatedService['id']; ?>" class="btn btn-primary" style="text-decoration: none; display: block; text-align: center;">
                                Подробнее
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

