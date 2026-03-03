<?php
session_start();
require_once __DIR__ . '/products_data.php';

$products = getProducts();
$productId = $_GET['id'] ?? '';

if (!isset($products[$productId])) {
    header('Location: products.php');
    exit;
}

$product = $products[$productId];
$flash = $_SESSION['flash_message'] ?? '';
unset($_SESSION['flash_message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($product['name']); ?> - Green Sense</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;700&family=Manrope:wght@400;500;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
  <?php include __DIR__ . '/partials/header.php'; ?>

  <main class="container">
    <section class="fade-in visible">
      <a class="section-note back-link" href="products.php">&larr; Back to Products</a>
      <h2>Product Details</h2>

      <?php if ($flash): ?>
        <p class="auth-msg auth-success"><?php echo htmlspecialchars($flash); ?></p>
      <?php endif; ?>

      <div class="detail-layout">
        <div class="detail-image-wrap">
          <img class="detail-image" src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
        </div>
        <div class="detail-content">
          <h3><?php echo htmlspecialchars($product['name']); ?></h3>
          <p><?php echo htmlspecialchars($product['details']); ?></p>
          <div class="detail-meta">
            <p><strong>Category:</strong> <?php echo htmlspecialchars(ucfirst($product['category'])); ?></p>
            <p><strong>Size:</strong> <?php echo htmlspecialchars($product['size']); ?></p>
            <p><strong>Origin:</strong> <?php echo htmlspecialchars($product['origin']); ?></p>
            <p><strong>Best Brew Method:</strong> <?php echo htmlspecialchars($product['brew']); ?></p>
          </div>
          <div class="detail-price">$<?php echo number_format((float) $product['price'], 2); ?></div>

          <form class="product-actions" action="cart_action.php" method="post">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>" />
            <input type="hidden" name="return_to" value="product_detail.php?id=<?php echo urlencode($product['id']); ?>" />
            <button class="btn btn-secondary btn-small" type="submit" name="action" value="add">Add to Cart</button>
            <button class="btn btn-buy btn-small" type="submit" name="action" value="buy">Buy Now</button>
          </form>
          <form class="wishlist-form" action="wishlist_action.php" method="post">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>" />
            <input type="hidden" name="return_to" value="product_detail.php?id=<?php echo urlencode($product['id']); ?>" />
            <button class="btn btn-wishlist btn-small" type="submit" name="action" value="add">Add to Wishlist</button>
          </form>
        </div>
      </div>
    </section>
  </main>

  <?php include __DIR__ . '/partials/footer.php'; ?>
  <?php include __DIR__ . '/chatbot.php'; ?>
</body>
</html>
