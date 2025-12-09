<?php
$pageTitle = 'Каталог запчастей - Автосервис';
$currentPage = 'catalog';

require_once __DIR__ . '/includes/header.php';

// Получение параметров фильтрации
$searchQuery = trim($_GET['search'] ?? '');
$categoryFilter = trim($_GET['category'] ?? '');

// Получение категорий для выпадающего списка
$categories = $partsCatalog->getCategories();

// Получение запчастей с учетом поиска и фильтра по категории
// Используем единый метод, который правильно обрабатывает оба параметра
$filteredParts = $partsCatalog->searchPartsWithCategory(
    $searchQuery, 
    !empty($categoryFilter) ? $categoryFilter : null
);
?>

<section class="container">
    <h1 style="text-align: center; margin-bottom: 2rem; color: var(--primary-color);">Каталог запчастей</h1>

    <div class="catalog-filters">
        <form method="GET" action="catalog.php" class="filter-group" id="searchForm">
            <input 
                type="text" 
                name="search" 
                id="searchInput"
                placeholder="Поиск по названию, бренду или категории..." 
                value="<?php echo htmlspecialchars($searchQuery); ?>"
                style="flex: 2;"
                autocomplete="off"
            >
            <select name="category" id="categorySelect">
                <option value="">Все категории</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category); ?>" 
                            <?php echo ($categoryFilter === $category) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Найти</button>
            <?php if (!empty($searchQuery) || !empty($categoryFilter)): ?>
                <a href="catalog.php" class="btn btn-outline">Сбросить</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (!empty($searchQuery) || !empty($categoryFilter)): ?>
        <div style="margin-bottom: 1rem; padding: 0.75rem; background: #e3f2fd; border-radius: 5px; color: #1976d2;">
            <strong>Активные фильтры:</strong>
            <?php if (!empty($searchQuery)): ?>
                <span style="margin-left: 0.5rem;">Поиск: "<?php echo htmlspecialchars($searchQuery); ?>"</span>
            <?php endif; ?>
            <?php if (!empty($categoryFilter)): ?>
                <span style="margin-left: 0.5rem;">Категория: <?php echo htmlspecialchars($categoryFilter); ?></span>
            <?php endif; ?>
            <span style="margin-left: 1rem; color: #666;">Найдено: <?php echo count($filteredParts); ?> запчастей</span>
        </div>
    <?php endif; ?>

    <?php if (empty($filteredParts)): ?>
        <div style="text-align: center; padding: 3rem; background: var(--bg-white); border-radius: 10px; box-shadow: var(--shadow);">
            <p style="font-size: 1.2rem; color: #666;">Запчасти не найдены. Попробуйте изменить параметры поиска.</p>
        </div>
    <?php else: ?>
        <div class="parts-grid">
            <?php foreach ($filteredParts as $part): ?>
                <div class="part-card">
                    <?php 
                    $imagePath = 'images/parts/' . ($part['image'] ?? 'placeholder.jpg');
                    $imageExists = file_exists($imagePath);
                    ?>
                    <?php if ($imageExists): ?>
                        <div style="margin-bottom: 1rem; border-radius: 8px; overflow: hidden; background: var(--bg-light); aspect-ratio: 4/3;">
                            <img src="<?php echo htmlspecialchars($imagePath); ?>" 
                                 alt="<?php echo htmlspecialchars($part['name']); ?>"
                                 style="width: 100%; height: 100%; object-fit: cover; display: block;">
                        </div>
                    <?php else: ?>
                        <div style="margin-bottom: 1rem; border-radius: 8px; background: linear-gradient(135deg, var(--bg-light) 0%, var(--border-color) 100%); aspect-ratio: 4/3; display: flex; align-items: center; justify-content: center;">
                            <div style="font-size: 3rem; opacity: 0.3;">🔧</div>
                        </div>
                    <?php endif; ?>
                    <span class="part-category"><?php echo htmlspecialchars($part['category']); ?></span>
                    <h3 class="part-name"><?php echo htmlspecialchars($part['name']); ?></h3>
                    <p class="part-brand">Бренд: <strong><?php echo htmlspecialchars($part['brand']); ?></strong></p>
                    <p class="part-description"><?php echo htmlspecialchars($part['description']); ?></p>
                    <div class="part-price"><?php echo number_format($part['price'], 0, ',', ' '); ?> ₸</div>
                    <div class="part-stock <?php 
                        echo $part['stock'] > 10 ? '' : ($part['stock'] > 0 ? 'low' : 'out'); 
                    ?>">
                        <?php if ($part['stock'] > 0): ?>
                            В наличии: <?php echo $part['stock']; ?> шт.
                        <?php else: ?>
                            Нет в наличии
                        <?php endif; ?>
                    </div>
                    <div style="margin-top: 1rem;">
                        <a href="part.php?id=<?php echo $part['id']; ?>" class="btn" style="width: 100%; display: block; text-align: center; text-decoration: none;">Подробнее</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<script>
// Автоматическая отправка формы при изменении категории
document.getElementById('categorySelect').addEventListener('change', function() {
    document.getElementById('searchForm').submit();
});

// Поиск по Enter в поле ввода
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('searchForm').submit();
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

