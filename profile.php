<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/products_data.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['auth_error'] = 'Please login to view your profile.';
    header('Location: auth.php');
    exit;
}

$userId = (int) $_SESSION['user_id'];
$success = '';
$error = '';
$flash = $_SESSION['flash_message'] ?? '';
unset($_SESSION['flash_message']);
$products = getProducts();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');

    if (strlen($name) < 2 || strlen($name) > 80) {
        $error = 'Name must be between 2 and 80 characters.';
    } else {
        try {
            $stmt = $pdo->prepare('UPDATE users SET name = ? WHERE id = ?');
            $stmt->execute([$name, $userId]);
            $_SESSION['user_name'] = $name;
            $success = 'Profile updated successfully.';
        } catch (PDOException $e) {
            $error = 'Unable to update profile right now. Please try again.';
        }
    }
}

$stmt = $pdo->prepare('SELECT id, name, email, created_at FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    $_SESSION['auth_error'] = 'User account not found. Please login again.';
    header('Location: logout.php');
    exit;
}

$wishlistStmt = $pdo->prepare('SELECT product_id, created_at FROM wishlist WHERE user_id = ? ORDER BY id DESC');
$wishlistStmt->execute([$userId]);
$wishlistRows = $wishlistStmt->fetchAll();

$ordersStmt = $pdo->prepare(
    'SELECT o.id AS order_id, o.payment_method, o.total_amount, o.status, o.created_at,
            oi.product_id, oi.product_name, oi.unit_price, oi.quantity, oi.line_total
     FROM orders o
     JOIN order_items oi ON oi.order_id = o.id
     WHERE o.user_id = ?
     ORDER BY o.id DESC, oi.id ASC'
);
$ordersStmt->execute([$userId]);
$orderRows = $ordersStmt->fetchAll();

$orders = [];
foreach ($orderRows as $row) {
    $orderId = (int) $row['order_id'];
    if (!isset($orders[$orderId])) {
        $orders[$orderId] = [
            'order_id' => $orderId,
            'payment_method' => $row['payment_method'],
            'total_amount' => $row['total_amount'],
            'status' => $row['status'],
            'created_at' => $row['created_at'],
            'items' => []
        ];
    }
    $orders[$orderId]['items'][] = [
        'product_id' => $row['product_id'],
        'product_name' => $row['product_name'],
        'unit_price' => $row['unit_price'],
        'quantity' => $row['quantity'],
        'line_total' => $row['line_total']
    ];
}
$initial = strtoupper(substr(trim((string) $user['name']), 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Profile - Green Sense</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;700&family=Manrope:wght@400;500;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
  <?php include __DIR__ . '/partials/header.php'; ?>

  <main class="container auth-main">
    <section class="auth-section fade-in visible profile-card">
      <h2>User Profile</h2>
      <p class="section-note">Manage your account details.</p>

      <?php if ($flash): ?>
        <p class="auth-msg auth-success"><?php echo htmlspecialchars($flash); ?></p>
      <?php endif; ?>
      <?php if ($success): ?>
        <p class="auth-msg auth-success"><?php echo htmlspecialchars($success); ?></p>
      <?php endif; ?>
      <?php if ($error): ?>
        <p class="auth-msg auth-error"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>

      <div class="profile-head">
        <div class="profile-avatar-wrap">
          <img class="profile-avatar" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=300&q=80" alt="Profile avatar" />
          <span class="profile-avatar-fallback"><?php echo htmlspecialchars($initial); ?></span>
        </div>
        <div class="profile-head-meta">
          <h3><?php echo htmlspecialchars($user['name']); ?></h3>
          <p class="section-note"><?php echo htmlspecialchars($user['email']); ?></p>
          <p class="section-note">Member Since: <?php echo htmlspecialchars($user['created_at']); ?></p>
        </div>
      </div>

      <form class="auth-form profile-form" method="post">
        <label for="profile-name">Full Name</label>
        <input id="profile-name" name="name" type="text" maxlength="80" value="<?php echo htmlspecialchars($user['name']); ?>" required />

        <label for="profile-email">Email</label>
        <input id="profile-email" type="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly />

        <label for="profile-created">Member Since</label>
        <input id="profile-created" type="text" value="<?php echo htmlspecialchars($user['created_at']); ?>" readonly />

        <button class="btn btn-secondary auth-btn" type="submit">Save Changes</button>
      </form>

      <div class="profile-sections">
        <div class="profile-block">
          <h3>Wishlist</h3>
          <?php if (!$wishlistRows): ?>
            <p class="section-note">No wishlist items yet.</p>
          <?php else: ?>
            <div class="wishlist-grid">
              <?php foreach ($wishlistRows as $wish): ?>
                <?php
                  $pid = $wish['product_id'];
                  if (!isset($products[$pid])) {
                      continue;
                  }
                  $item = $products[$pid];
                ?>
                <article class="wishlist-item">
                  <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" />
                  <div>
                    <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                    <p class="section-note">$<?php echo number_format((float) $item['price'], 2); ?> | <?php echo htmlspecialchars($item['size']); ?></p>
                  </div>
                  <div class="wishlist-actions">
                    <form action="cart_action.php" method="post">
                      <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['id']); ?>" />
                      <input type="hidden" name="quantity" value="1" />
                      <input type="hidden" name="return_to" value="profile.php" />
                      <button class="btn btn-secondary btn-small" type="submit" name="action" value="add">Add to Cart</button>
                    </form>
                    <form action="wishlist_action.php" method="post">
                      <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['id']); ?>" />
                      <input type="hidden" name="return_to" value="profile.php" />
                      <button class="btn btn-danger btn-small" type="submit" name="action" value="remove">Remove</button>
                    </form>
                  </div>
                </article>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

        <div class="profile-block">
          <h3>Order History</h3>
          <?php if (!$orders): ?>
            <p class="section-note">No orders yet.</p>
          <?php else: ?>
            <div class="order-list">
              <?php foreach ($orders as $order): ?>
                <article class="order-card">
                  <div class="order-head">
                    <strong>Order #<?php echo (int) $order['order_id']; ?></strong>
                    <span><?php echo htmlspecialchars($order['created_at']); ?></span>
                  </div>
                  <p class="section-note">
                    Payment: <?php echo htmlspecialchars(strtoupper($order['payment_method'])); ?> |
                    Status: <?php echo htmlspecialchars(ucfirst($order['status'])); ?> |
                    Total: $<?php echo number_format((float) $order['total_amount'], 2); ?>
                  </p>
                  <div class="order-items">
                    <?php foreach ($order['items'] as $item): ?>
                      <div class="order-item-row">
                        <span><?php echo htmlspecialchars($item['product_name']); ?></span>
                        <span><?php echo (int) $item['quantity']; ?> x $<?php echo number_format((float) $item['unit_price'], 2); ?></span>
                        <span>$<?php echo number_format((float) $item['line_total'], 2); ?></span>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </article>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>
  </main>

  <?php include __DIR__ . '/partials/footer.php'; ?>
  <?php include __DIR__ . '/chatbot.php'; ?>
</body>
</html>
