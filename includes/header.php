<?php
// Установка кодировки
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');

// Подключение автозагрузчика
require_once __DIR__ . '/../vendor/autoload.php';

use App\PartsCatalog;
use App\Services;
use App\Cart;

// Инициализация классов
$partsCatalog = new PartsCatalog();
$services = new Services();
Cart::init();
$cartCount = Cart::getCount();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Автосервис - Профессиональный ремонт автомобилей'; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1>🚗 Автосервис</h1>
                </div>
                <nav class="nav">
                    <a href="index.php" class="nav-link <?php echo ($currentPage === 'home') ? 'active' : ''; ?>">Главная</a>
                    <a href="catalog.php" class="nav-link <?php echo ($currentPage === 'catalog') ? 'active' : ''; ?>">Каталог запчастей</a>
                    <a href="services.php" class="nav-link <?php echo ($currentPage === 'services') ? 'active' : ''; ?>">Услуги</a>
                    <a href="contact.php" class="nav-link <?php echo ($currentPage === 'contact') ? 'active' : ''; ?>">Контакты</a>
                    <a href="orders.php" class="nav-link <?php echo ($currentPage === 'orders') ? 'active' : ''; ?>">Заказы</a>
                    <a href="cart.php" class="nav-link" style="position: relative;">
                        🛒 Корзина
                        <?php if ($cartCount > 0): ?>
                            <span style="position: absolute; top: -8px; right: -8px; background: var(--secondary-color); color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: bold;">
                                <?php echo $cartCount; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </nav>
            </div>
        </div>
    </header>
    <main class="main-content">

