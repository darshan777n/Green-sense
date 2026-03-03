<?php
session_start();
require_once __DIR__ . '/products_data.php';

$products = getProducts();
$productId = $_SESSION['buy_now_product_id'] ?? '';
unset($_SESSION['buy_now_product_id']);

if (!$productId || !isset($products[$productId])) {
    header('Location: products.php');
    exit;
}

$product = $products[$productId];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Buy Now - Green Sense</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;700&family=Manrope:wght@400;500;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
  <?php include __DIR__ . '/partials/header.php'; ?>

  <main class="container auth-main">
    <section class="auth-section fade-in visible">
      <h2>Order Confirmed</h2>
      <p class="auth-msg auth-success">
        You selected <strong><?php echo htmlspecialchars($product['name']); ?></strong> for $<?php echo number_format((float) $product['price'], 2); ?>.
      </p>
      <p class="section-note">This is a demo buy-now flow. You can continue shopping.</p>
      <div class="buy-links">
        <a class="btn btn-secondary btn-inline" href="products.php">Back to Products</a>
        <a class="btn btn-buy btn-inline" href="cart.php">View Cart</a>
      </div>
    </section>
  </main>

  <?php include __DIR__ . '/partials/footer.php'; ?>
  <?php include __DIR__ . '/chatbot.php'; ?>
</body>
</html>
