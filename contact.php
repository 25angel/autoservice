<?php
$pageTitle = 'Контакты - Автосервис';
$currentPage = 'contact';

require_once __DIR__ . '/includes/header.php';

// Обработка формы
$messageSent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // В реальном проекте здесь была бы отправка email или сохранение в БД
    $messageSent = true;
}
?>

<section class="container">
    <h1 style="text-align: center; margin-bottom: 2rem; color: var(--primary-color);">Контакты</h1>

    <?php if ($messageSent): ?>
        <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; margin-bottom: 2rem; text-align: center;">
            ✅ Спасибо! Ваше сообщение отправлено. Мы свяжемся с вами в ближайшее время.
        </div>
    <?php endif; ?>

    <div class="contact-info">
        <div class="contact-item">
            <div class="contact-icon">📍</div>
            <div>
                <h3>Адрес</h3>
                <p>г. Астана, проспект Кабанбай батыра, д. 32</p>
                <p style="color: #666; font-size: 0.9rem;">Рядом с ТРЦ "Хан Шатыр"</p>
            </div>
        </div>

        <div class="contact-item">
            <div class="contact-icon">📞</div>
            <div>
                <h3>Телефон</h3>
                <p><strong>+7 (772) 123-45-67</strong></p>
                <p style="color: #666; font-size: 0.9rem;">Звонки принимаются ежедневно</p>
            </div>
        </div>

        <div class="contact-item">
            <div class="contact-icon">📧</div>
            <div>
                <h3>Email</h3>
                <p><strong>info@autoservice.kz</strong></p>
                <p style="color: #666; font-size: 0.9rem;">Ответим в течение 24 часов</p>
            </div>
        </div>

        <div class="contact-item">
            <div class="contact-icon">🕐</div>
            <div>
                <h3>Режим работы</h3>
                <p><strong>Пн-Пт:</strong> 9:00 - 20:00</p>
                <p><strong>Сб-Вс:</strong> 10:00 - 18:00</p>
            </div>
        </div>
    </div>

    <div class="contact-grid" style="margin-top: 3rem;">
        <div class="contact-section">
            <h2 style="color: var(--primary-color); margin-bottom: 1rem;">Как нас найти</h2>
            <p style="margin-bottom: 1rem;">
                Наш автосервис расположен в центре Астаны с хорошей транспортной доступностью. 
                Рядом есть бесплатная парковка для клиентов.
            </p>
            <div style="background: var(--bg-white); border-radius: 12px; overflow: hidden; box-shadow: var(--shadow); border: 1px solid var(--border-color);">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2501.234567890123!2d71.4300!3d51.1694!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNTHCsDEwJzA5LjgiTiA3McKwMjUnNDguMCJF!5e0!3m2!1sru!2skz!4v1234567890123!5m2!1sru!2skz" 
                    width="100%" 
                    height="450" 
                    style="border:0; display: block;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Карта автосервиса в Астане, проспект Кабанбай батыра">
                </iframe>
            </div>
            <p style="margin-top: 1rem; text-align: center; color: var(--text-light); font-size: 0.9rem;">
                <a href="https://www.google.com/maps/search/?api=1&query=проспект+Кабанбай+батыра+32,+Астана,+Казахстан" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   style="color: var(--accent-color); text-decoration: none; font-weight: 500;">
                    📍 Открыть в Google Maps
                </a>
            </p>
        </div>

        <div class="contact-form">
            <h2 style="color: var(--primary-color); margin-bottom: 1rem;">Напишите нам</h2>
            <form method="POST" action="contact.php">
                <div class="form-group">
                    <label for="name">Ваше имя *</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="phone">Телефон *</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
                </div>
                <div class="form-group">
                    <label for="message">Сообщение *</label>
                    <textarea id="message" name="message" required></textarea>
                </div>
                <button type="submit" name="submit" class="btn btn-primary" style="width: 100%;">Отправить сообщение</button>
            </form>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

