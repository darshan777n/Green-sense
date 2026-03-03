<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/products_data.php';

$products = getProducts();

$productId = $_SESSION['buy_now_product_id'] ?? '';
$quantity = (int) ($_SESSION['buy_now_quantity'] ?? 1);
$quantity = max(1, min(20, $quantity));

if (!$productId || !isset($products[$productId])) {
    header('Location: products.php');
    exit;
}

$product = $products[$productId];
$subtotal = (float) $product['price'] * $quantity;

$paymentSuccess = false;
 $persistError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = $_POST['payment_method'] ?? '';
    if (in_array($method, ['card', 'upi', 'cod'], true)) {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['auth_error'] = 'Please login to complete payment.';
            header('Location: auth.php');
            exit;
        }

        try {
            $pdo->beginTransaction();
            $orderStmt = $pdo->prepare('INSERT INTO orders (user_id, payment_method, total_amount, status) VALUES (?, ?, ?, ?)');
            $orderStmt->execute([(int) $_SESSION['user_id'], $method, $subtotal, 'paid']);
            $orderId = (int) $pdo->lastInsertId();

            $itemStmt = $pdo->prepare(
                'INSERT INTO order_items (order_id, product_id, product_name, unit_price, quantity, line_total)
                 VALUES (?, ?, ?, ?, ?, ?)'
            );
            $itemStmt->execute([
                $orderId,
                $product['id'],
                $product['name'],
                $product['price'],
                $quantity,
                $subtotal
            ]);

            $pdo->commit();
            $paymentSuccess = true;
            unset($_SESSION['buy_now_product_id'], $_SESSION['buy_now_quantity']);
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $persistError = 'Payment processed but order could not be saved. Please contact support.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Payment - Green Sense</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;700&family=Manrope:wght@400;500;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
  <?php include __DIR__ . '/partials/header.php'; ?>
  <?php if ($paymentSuccess): ?>
    <div id="orderSuccessOverlay" class="order-success-overlay" aria-hidden="true">
      <div class="order-success-card">
        <h3>Thank you for order Green coffee.</h3>
        <p>It will reach soon.</p>
      </div>
    </div>
    <script>
      (function () {
        const overlay = document.getElementById('orderSuccessOverlay');
        if (!overlay) {
          return;
        }
        setTimeout(() => {
          overlay.classList.add('hide');
        }, 1800);
        setTimeout(() => {
          overlay.remove();
        }, 2500);
      })();
    </script>
  <?php endif; ?>

  <main class="container auth-main">
    <section class="auth-section fade-in visible">
      <h2>Temporary Payment Page</h2>

      <?php if ($paymentSuccess): ?>
        <p class="auth-msg auth-success">
          Payment successful for <?php echo (int) $quantity; ?> x <?php echo htmlspecialchars($product['name']); ?>.
        </p>
        <div class="buy-links">
          <a class="btn btn-secondary btn-inline" href="products.php">Continue Shopping</a>
          <a class="btn btn-detail btn-inline" href="cart.php">Back to Cart</a>
        </div>
      <?php else: ?>
        <?php if ($persistError): ?>
          <p class="auth-msg auth-error"><?php echo htmlspecialchars($persistError); ?></p>
        <?php endif; ?>
        <div class="payment-summary">
          <img class="cart-thumb" src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
          <div>
            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
            <p class="section-note">Quantity: <?php echo (int) $quantity; ?></p>
            <p class="detail-price">$<?php echo number_format($subtotal, 2); ?></p>
          </div>
        </div>

        <form class="payment-form" method="post">
          <h3>Select Payment Method</h3>
          <label><input type="radio" name="payment_method" value="card" required /> Credit / Debit Card</label>
          <label><input type="radio" name="payment_method" value="upi" required /> UPI</label>
          <label><input type="radio" name="payment_method" value="cod" required /> Cash on Delivery</label>
          <button class="btn btn-buy btn-inline" type="submit">Pay Now</button>
        </form>
      <?php endif; ?>
    </section>
  </main>

  <?php include __DIR__ . '/partials/footer.php'; ?>
  <?php include __DIR__ . '/chatbot.php'; ?>
</body>
</html>
