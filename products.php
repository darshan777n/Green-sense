<?php
session_start();
require_once __DIR__ . '/products_data.php';

$products = getProducts();
$flash = $_SESSION['flash_message'] ?? '';
unset($_SESSION['flash_message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Products - Green Sense</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;700&family=Manrope:wght@400;500;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
  <?php include __DIR__ . '/partials/header.php'; ?>

  <main class="container">
    <section class="fade-in visible product-list-section">
      <h2>All Products</h2>
      <p class="section-note">Choose your favorites, add to cart, or open full product details.</p>

      <?php if ($flash): ?>
        <p class="auth-msg auth-success"><?php echo htmlspecialchars($flash); ?></p>
      <?php endif; ?>

      <div class="filters">
        <button class="filter-btn active" data-filter="all">All</button>
        <button class="filter-btn" data-filter="coffee">Coffee</button>
        <button class="filter-btn" data-filter="tea">Tea</button>
      </div>

      <div class="products-grid" id="productsGrid">
        <?php foreach ($products as $product): ?>
          <article class="product-card" data-category="<?php echo htmlspecialchars($product['category']); ?>">
            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
            <div class="product-content">
              <h3><?php echo htmlspecialchars($product['name']); ?></h3>
              <p><?php echo htmlspecialchars($product['description']); ?></p>
              <div class="price-row">
                <div class="price">$<?php echo number_format((float) $product['price'], 2); ?></div>
                <small><?php echo htmlspecialchars($product['size']); ?></small>
              </div>
              <div class="qty-control">
                <button type="button" class="qty-btn qty-minus" aria-label="Decrease quantity">-</button>
                <input class="qty-input" type="number" value="1" min="1" max="20" readonly />
                <button type="button" class="qty-btn qty-plus" aria-label="Increase quantity">+</button>
              </div>
              <form class="product-actions" action="cart_action.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>" />
                <input type="hidden" name="return_to" value="products.php" />
                <input type="hidden" class="qty-hidden" name="quantity" value="1" />
                <button class="btn btn-secondary btn-small" type="submit" name="action" value="add">Add to Cart</button>
                <a class="btn btn-detail btn-small btn-inline" href="product_detail.php?id=<?php echo urlencode($product['id']); ?>">View More Details</a>
              </form>
              <form class="wishlist-form" action="wishlist_action.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>" />
                <input type="hidden" name="return_to" value="products.php" />
                <button class="btn btn-wishlist btn-small" type="submit" name="action" value="add">Add to Wishlist</button>
              </form>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
  </main>

  <?php include __DIR__ . '/partials/footer.php'; ?>

  <script>
    const filterButtons = document.querySelectorAll('.filter-btn');
    const productCards = document.querySelectorAll('.product-card');
    const qtyMinusButtons = document.querySelectorAll('.qty-minus');
    const qtyPlusButtons = document.querySelectorAll('.qty-plus');

    filterButtons.forEach((button) => {
      button.addEventListener('click', () => {
        filterButtons.forEach((btn) => btn.classList.remove('active'));
        button.classList.add('active');

        const selected = button.dataset.filter;
        productCards.forEach((card) => {
          const isMatch = selected === 'all' || card.dataset.category === selected;
          card.classList.toggle('hidden', !isMatch);
        });
      });
    });

    function updateQty(card, nextValue) {
      const qtyInput = card.querySelector('.qty-input');
      const qtyHidden = card.querySelector('.qty-hidden');
      const value = Math.max(1, Math.min(20, nextValue));
      qtyInput.value = value;
      qtyHidden.value = value;
    }

    qtyMinusButtons.forEach((button) => {
      button.addEventListener('click', () => {
        const card = button.closest('.product-card');
        const qtyInput = card.querySelector('.qty-input');
        updateQty(card, Number(qtyInput.value) - 1);
      });
    });

    qtyPlusButtons.forEach((button) => {
      button.addEventListener('click', () => {
        const card = button.closest('.product-card');
        const qtyInput = card.querySelector('.qty-input');
        updateQty(card, Number(qtyInput.value) + 1);
      });
    });
  </script>
  <?php include __DIR__ . '/chatbot.php'; ?>
</body>
</html>
