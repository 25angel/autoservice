<?php
$pageTitle = 'Корзина - Автосервис';
$currentPage = 'cart';

require_once __DIR__ . '/includes/header.php';

use App\Cart;
use App\PartsCatalog;

Cart::init();
$partsCatalog = new PartsCatalog();

// Обработка действий
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $partId = (int)($_POST['part_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 1);
        
        if ($partId > 0) {
            $part = $partsCatalog->getPartById($partId);
            if ($part && $part['stock'] >= $quantity) {
                Cart::add($partId, $quantity, $part);
                header('Location: cart.php?added=1');
                exit;
            }
        }
    } elseif ($action === 'update') {
        $partId = (int)($_POST['part_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 1);
        
        if ($partId > 0) {
            $part = $partsCatalog->getPartById($partId);
            if ($part && $part['stock'] >= $quantity) {
                Cart::update($partId, $quantity);
            }
        }
        header('Location: cart.php');
        exit;
    } elseif ($action === 'remove') {
        $partId = (int)($_POST['part_id'] ?? 0);
        if ($partId > 0) {
            Cart::remove($partId);
        }
        header('Location: cart.php');
        exit;
    } elseif ($action === 'clear') {
        Cart::clear();
        header('Location: cart.php');
        exit;
    }
}

$cartItems = Cart::getAll();
$cartTotal = Cart::getTotal();
?>

<section class="container">
    <h1 style="text-align: center; margin-bottom: 2rem; color: var(--primary-color);">Корзина</h1>

    <?php if (isset($_GET['added'])): ?>
        <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; margin-bottom: 2rem;">
            ✅ Товар добавлен в корзину!
        </div>
    <?php endif; ?>

    <?php if (empty($cartItems)): ?>
        <div style="text-align: center; padding: 3rem; background: var(--bg-white); border-radius: 10px; box-shadow: var(--shadow);">
            <div style="font-size: 4rem; margin-bottom: 1rem;">🛒</div>
            <p style="font-size: 1.2rem; color: #666; margin-bottom: 2rem;">Ваша корзина пуста</p>
            <a href="catalog.php" class="btn btn-primary">Перейти в каталог</a>
        </div>
    <?php else: ?>
        <div style="display: grid; gap: 2rem;">
            <!-- Список товаров -->
            <div style="background: var(--bg-white); border-radius: 10px; padding: 2rem; box-shadow: var(--shadow);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h2 style="color: var(--primary-color); margin: 0;">Товары в корзине</h2>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="clear">
                        <button type="submit" onclick="return confirm('Очистить корзину?')" 
                                style="background: transparent; border: 1px solid var(--secondary-color); color: var(--secondary-color); padding: 0.5rem 1rem; border-radius: 5px; cursor: pointer;">
                            Очистить корзину
                        </button>
                    </form>
                </div>

                <div style="display: grid; gap: 1.5rem;">
                    <?php foreach ($cartItems as $item): ?>
                        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 1rem; align-items: center; padding: 1rem; background: var(--bg-light); border-radius: 5px;">
                            <div>
                                <h3 style="margin: 0 0 0.5rem 0; color: var(--primary-color);">
                                    <a href="part.php?id=<?php echo $item['part_id']; ?>" style="color: inherit; text-decoration: none;">
                                        <?php echo htmlspecialchars($item['part_name']); ?>
                                    </a>
                                </h3>
                                <p style="margin: 0; color: #666; font-size: 0.9rem;">Бренд: <?php echo htmlspecialchars($item['part_brand']); ?></p>
                            </div>
                            
                            <div>
                                <form method="POST" style="display: flex; gap: 0.5rem; align-items: center;">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="part_id" value="<?php echo $item['part_id']; ?>">
                                    <input 
                                        type="number" 
                                        name="quantity" 
                                        value="<?php echo $item['quantity']; ?>" 
                                        min="1" 
                                        max="<?php echo $item['stock']; ?>"
                                        style="width: 60px; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 5px;"
                                        onchange="this.form.submit()"
                                    >
                                </form>
                            </div>
                            
                            <div style="text-align: center;">
                                <strong><?php echo number_format($item['price'], 0, ',', ' '); ?> ₸</strong>
                            </div>
                            
                            <div style="text-align: center;">
                                <strong style="color: var(--secondary-color);">
                                    <?php echo number_format($item['price'] * $item['quantity'], 0, ',', ' '); ?> ₸
                                </strong>
                            </div>
                            
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="part_id" value="<?php echo $item['part_id']; ?>">
                                <button type="submit" style="background: var(--secondary-color); color: white; border: none; padding: 0.5rem 1rem; border-radius: 5px; cursor: pointer;">
                                    ✕
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Итого -->
            <div style="background: var(--bg-white); border-radius: 10px; padding: 2rem; box-shadow: var(--shadow);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h2 style="color: var(--primary-color); margin: 0;">Итого</h2>
                    <div style="font-size: 2rem; color: var(--secondary-color); font-weight: bold;">
                        <?php echo number_format($cartTotal, 0, ',', ' '); ?> ₸
                    </div>
                </div>
                <div style="display: grid; gap: 1rem;">
                    <a href="catalog.php" class="btn btn-outline" style="text-align: center; text-decoration: none;">Продолжить покупки</a>
                    <a href="checkout.php" class="btn btn-primary" style="text-align: center; text-decoration: none;">Оформить заказ</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

