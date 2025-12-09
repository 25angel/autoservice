<?php
$pageTitle = 'Главная - Автосервис';
$currentPage = 'home';

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="container">
        <h2>Добро пожаловать в наш автосервис!</h2>
        <p>Профессиональный ремонт и обслуживание автомобилей с 2010 года</p>
    </div>
</section>

<section class="container">
    <div class="cards-grid">
        <div class="card">
            <div class="card-header">
                <div class="card-icon">🔧</div>
                <h3 class="card-title">Качественный ремонт</h3>
            </div>
            <p class="card-description">
                Опытные мастера с многолетним стажем работы. Используем только оригинальные запчасти и современное оборудование.
            </p>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-icon">⚡</div>
                <h3 class="card-title">Быстрое обслуживание</h3>
            </div>
            <p class="card-description">
                Большинство работ выполняем в день обращения. Гарантия на все виды работ и запчасти.
            </p>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-icon">💰</div>
                <h3 class="card-title">Доступные цены</h3>
            </div>
            <p class="card-description">
                Честные цены без переплат. Прозрачная система расчетов. Принимаем карты и наличные.
            </p>
        </div>
    </div>

    <div style="margin-top: 3rem;">
        <h2 style="text-align: center; margin-bottom: 2rem; color: var(--primary-color);">Почему выбирают нас?</h2>
        <div class="cards-grid">
            <div class="card">
                <h3 class="card-title">✅ 10+ лет опыта</h3>
                <p class="card-description">Более 10 лет успешной работы на рынке автосервисов</p>
            </div>
            <div class="card">
                <h3 class="card-title">✅ Оригинальные запчасти</h3>
                <p class="card-description">Работаем только с проверенными поставщиками</p>
            </div>
            <div class="card">
                <h3 class="card-title">✅ Гарантия качества</h3>
                <p class="card-description">Предоставляем гарантию на все виды работ</p>
            </div>
            <div class="card">
                <h3 class="card-title">✅ Современное оборудование</h3>
                <p class="card-description">Используем новейшие диагностические системы</p>
            </div>
        </div>
    </div>

    <div style="margin-top: 3rem; text-align: center;">
        <a href="catalog.php" class="btn btn-primary" style="font-size: 1.2rem; padding: 1rem 2rem;">
            Перейти в каталог запчастей →
        </a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
