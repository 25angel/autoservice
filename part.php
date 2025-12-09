<?php
$pageTitle = 'Запчасть - Автосервис';
$currentPage = 'catalog';

require_once __DIR__ . '/includes/header.php';

// Получение ID запчасти
$partId = (int)($_GET['id'] ?? 0);

if (!$partId) {
    header('Location: catalog.php');
    exit;
}

// Получение запчасти
$part = $partsCatalog->getPartById($partId);

if (!$part) {
    header('Location: catalog.php');
    exit;
}

// Получение похожих запчастей из той же категории
$relatedParts = $partsCatalog->getPartsByCategory($part['category']);
$relatedParts = array_filter($relatedParts, function($p) use ($partId) {
    return $p['id'] !== $partId;
});
$relatedParts = array_slice($relatedParts, 0, 4); // Показываем максимум 4
?>

<section class="container">
    <div style="margin-bottom: 1rem;">
        <a href="catalog.php" style="color: var(--accent-color); text-decoration: none;">← Вернуться в каталог</a>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-bottom: 3rem;">
        <!-- Левая колонка - изображение -->
        <div>
            <div style="background: var(--bg-white); border-radius: 12px; padding: 1.5rem; box-shadow: var(--shadow); border: 1px solid var(--border-color); overflow: hidden;">
                <?php 
                $imagePath = 'images/parts/' . ($part['image'] ?? 'placeholder.jpg');
                $imageExists = file_exists($imagePath);
                ?>
                <?php if ($imageExists): ?>
                    <img src="<?php echo htmlspecialchars($imagePath); ?>" 
                         alt="<?php echo htmlspecialchars($part['name']); ?>"
                         style="width: 100%; height: auto; border-radius: 8px; display: block;">
                <?php else: ?>
                    <div style="background: linear-gradient(135deg, var(--bg-light) 0%, var(--border-color) 100%); border-radius: 8px; padding: 4rem 2rem; text-align: center; min-height: 400px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <div style="font-size: 5rem; margin-bottom: 1rem; opacity: 0.5;">🔧</div>
                        <p style="color: var(--text-light); font-size: 0.9rem;">Изображение запчасти</p>
                        <p style="color: var(--text-light); font-size: 0.75rem; margin-top: 0.5rem;">Добавьте изображение в images/parts/</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Правая колонка - информация -->
        <div>
            <span class="part-category"><?php echo htmlspecialchars($part['category']); ?></span>
            <h1 style="font-size: 2rem; color: var(--primary-color); margin: 1rem 0;"><?php echo htmlspecialchars($part['name']); ?></h1>
            <p style="font-size: 1.2rem; color: #666; margin-bottom: 1rem;">
                Бренд: <strong><?php echo htmlspecialchars($part['brand']); ?></strong>
            </p>
            
            <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px; margin: 2rem 0;">
                <div style="font-size: 2.5rem; color: var(--secondary-color); font-weight: bold; margin-bottom: 0.5rem;">
                    <?php echo number_format($part['price'], 0, ',', ' '); ?> ₸
                </div>
                <div class="part-stock <?php 
                    echo $part['stock'] > 10 ? '' : ($part['stock'] > 0 ? 'low' : 'out'); 
                ?>">
                    <?php if ($part['stock'] > 0): ?>
                        В наличии: <?php echo $part['stock']; ?> шт.
                    <?php else: ?>
                        Нет в наличии
                    <?php endif; ?>
                </div>
            </div>

            <div style="margin: 2rem 0;">
                <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Описание</h3>
                <p style="line-height: 1.8; color: #666;"><?php echo nl2br(htmlspecialchars($part['description'])); ?></p>
            </div>

            <?php if ($part['stock'] > 0): ?>
                <form method="POST" action="cart.php" style="margin-top: 2rem;">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="part_id" value="<?php echo $part['id']; ?>">
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <label style="font-weight: 500;">Количество:</label>
                        <input 
                            type="number" 
                            name="quantity" 
                            value="1" 
                            min="1" 
                            max="<?php echo $part['stock']; ?>"
                            style="width: 80px; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 5px;"
                            required
                        >
                        <button type="submit" class="btn" style="flex: 1;">Добавить в корзину</button>
                    </div>
                </form>
            <?php else: ?>
                <div style="padding: 1rem; background: #fff3cd; border-radius: 5px; color: #856404; margin-top: 2rem;">
                    <strong>Товар временно отсутствует</strong>
                    <p style="margin: 0.5rem 0 0 0; font-size: 0.9rem;">Свяжитесь с нами, чтобы узнать о поступлении товара.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Похожие запчасти -->
    <?php if (!empty($relatedParts)): ?>
        <div style="margin-top: 4rem;">
            <h2 style="color: var(--primary-color); margin-bottom: 2rem;">Похожие запчасти</h2>
            <div class="parts-grid">
                <?php foreach ($relatedParts as $relatedPart): ?>
                    <div class="part-card">
                        <?php 
                        $relatedImagePath = 'images/parts/' . ($relatedPart['image'] ?? 'placeholder.jpg');
                        $relatedImageExists = file_exists($relatedImagePath);
                        ?>
                        <?php if ($relatedImageExists): ?>
                            <div style="margin-bottom: 1rem; border-radius: 8px; overflow: hidden; background: var(--bg-light); aspect-ratio: 4/3;">
                                <img src="<?php echo htmlspecialchars($relatedImagePath); ?>" 
                                     alt="<?php echo htmlspecialchars($relatedPart['name']); ?>"
                                     style="width: 100%; height: 100%; object-fit: cover; display: block;">
                            </div>
                        <?php else: ?>
                            <div style="margin-bottom: 1rem; border-radius: 8px; background: linear-gradient(135deg, var(--bg-light) 0%, var(--border-color) 100%); aspect-ratio: 4/3; display: flex; align-items: center; justify-content: center;">
                                <div style="font-size: 2.5rem; opacity: 0.3;">🔧</div>
                            </div>
                        <?php endif; ?>
                        <span class="part-category"><?php echo htmlspecialchars($relatedPart['category']); ?></span>
                        <h3 class="part-name">
                            <a href="part.php?id=<?php echo $relatedPart['id']; ?>" style="color: inherit; text-decoration: none;">
                                <?php echo htmlspecialchars($relatedPart['name']); ?>
                            </a>
                        </h3>
                        <p class="part-brand">Бренд: <strong><?php echo htmlspecialchars($relatedPart['brand']); ?></strong></p>
                        <p class="part-description"><?php echo htmlspecialchars($relatedPart['description']); ?></p>
                        <div class="part-price"><?php echo number_format($relatedPart['price'], 0, ',', ' '); ?> ₸</div>
                        <div class="part-stock <?php 
                            echo $relatedPart['stock'] > 10 ? '' : ($relatedPart['stock'] > 0 ? 'low' : 'out'); 
                        ?>">
                            <?php if ($relatedPart['stock'] > 0): ?>
                                В наличии: <?php echo $relatedPart['stock']; ?> шт.
                            <?php else: ?>
                                Нет в наличии
                            <?php endif; ?>
                        </div>
                        <div style="margin-top: 1rem;">
                            <a href="part.php?id=<?php echo $relatedPart['id']; ?>" class="btn" style="width: 100%; display: block; text-align: center; text-decoration: none;">Подробнее</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

