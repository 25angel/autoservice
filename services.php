<?php
$pageTitle = 'Услуги - Автосервис';
$currentPage = 'services';

require_once __DIR__ . '/includes/header.php';

$allServices = $services->getAllServices();
?>

<section class="container">
    <h1 style="text-align: center; margin-bottom: 2rem; color: var(--primary-color);">Наши услуги</h1>
    <p style="text-align: center; font-size: 1.1rem; color: #666; margin-bottom: 3rem;">
        Полный спектр услуг по ремонту и обслуживанию автомобилей
    </p>

    <div class="services-grid">
        <?php foreach ($allServices as $service): ?>
            <div class="service-card">
                <div class="service-icon"><?php echo $service['icon']; ?></div>
                <h3 class="card-title"><?php echo htmlspecialchars($service['name']); ?></h3>
                <p class="card-description"><?php echo htmlspecialchars($service['description']); ?></p>
                <div class="card-price">от <?php echo number_format($service['price'], 0, ',', ' '); ?> ₸</div>
                <p class="service-duration">⏱️ Время выполнения: <?php echo htmlspecialchars($service['duration']); ?></p>
                <div style="margin-top: 1.5rem;">
                    <a href="service.php?id=<?php echo $service['id']; ?>" class="btn btn-primary" style="text-decoration: none; display: block; text-align: center;">
                        Подробнее
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div style="margin-top: 3rem; background: var(--bg-white); padding: 2rem; border-radius: 10px; box-shadow: var(--shadow);">
        <h2 style="color: var(--primary-color); margin-bottom: 1rem;">Дополнительная информация</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
            <div>
                <h3 style="color: var(--accent-color); margin-bottom: 0.5rem;">📋 Гарантия</h3>
                <p>На все виды работ предоставляется гарантия от 6 до 24 месяцев в зависимости от типа услуги.</p>
            </div>
            <div>
                <h3 style="color: var(--accent-color); margin-bottom: 0.5rem;">🔧 Оборудование</h3>
                <p>Используем только профессиональное оборудование ведущих мировых производителей.</p>
            </div>
            <div>
                <h3 style="color: var(--accent-color); margin-bottom: 0.5rem;">👨‍🔧 Мастера</h3>
                <p>Наши специалисты регулярно проходят обучение и имеют сертификаты по работе с современными системами.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

