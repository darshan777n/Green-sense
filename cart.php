<?php
session_start();
require_once __DIR__ . '/products_data.php';

$products = getProducts();
$cart = $_SESSION['cart'] ?? [];
$total = 0;
$flash = $_SESSION['flash_message'] ?? '';
unset($_SESSION['flash_message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cart - Green Sense</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;700&family=Manrope:wght@400;500;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
  <?php include __DIR__ . '/partials/header.php'; ?>

  <main class="container">
    <section class="fade-in visible">
      <h2>Your Cart</h2>
      <?php if ($flash): ?>
        <p class="auth-msg auth-success"><?php echo htmlspecialchars($flash); ?></p>
      <?php endif; ?>
      <?php if (!$cart): ?>
        <p class="section-note">Your cart is empty. <a href="products.php">Browse products</a>.</p>
      <?php else: ?>
        <div class="cart-list">
          <?php foreach ($cart as $productId => $qty): ?>
            <?php if (!isset($products[$productId])) { continue; } ?>
            <?php
              $item = $products[$productId];
              $lineTotal = (float) $item['price'] * (int) $qty;
              $total += $lineTotal;
            ?>
            <article class="cart-item">
              <div class="cart-item-left">
                <img class="cart-thumb" src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" />
                <div>
                  <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                  <p class="section-note">Quantity: <?php echo (int) $qty; ?></p>
                </div>
              </div>
              <div class="cart-item-right">
                <div class="price">$<?php echo number_format($lineTotal, 2); ?></div>
                <div class="cart-item-actions">
                  <form action="cart_action.php" method="post">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($productId); ?>" />
                    <input type="hidden" name="quantity" value="<?php echo (int) $qty; ?>" />
                    <input type="hidden" name="return_to" value="cart.php" />
                    <button class="btn btn-buy btn-small" type="submit" name="action" value="buy">Buy Now</button>
                  </form>
                  <form action="cart_action.php" method="post">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($productId); ?>" />
                    <input type="hidden" name="return_to" value="cart.php" />
                    <button class="btn btn-danger btn-small" type="submit" name="action" value="remove">Remove</button>
                  </form>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
        <p class="cart-total">Total: $<?php echo number_format($total, 2); ?></p>
      <?php endif; ?>
    </section>
  </main>

  <?php include __DIR__ . '/partials/footer.php'; ?>
  <?php include __DIR__ . '/chatbot.php'; ?>
</body>
</html>
