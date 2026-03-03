<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../db.php';

$isLoggedIn = isset($_SESSION['user_id']);
$displayName = $isLoggedIn ? $_SESSION['user_name'] : 'Guest';
$cartCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;

$sessionId = session_id();
$visitStmt = $pdo->prepare(
    'INSERT INTO site_visits (session_id, first_seen, last_seen, visit_count)
     VALUES (?, NOW(), NOW(), 1)
     ON DUPLICATE KEY UPDATE last_seen = NOW(), visit_count = visit_count + 1'
);
$visitStmt->execute([$sessionId]);
$isHomePage = basename($_SERVER['PHP_SELF']) === 'index.php';
?>
<?php if ($isHomePage): ?>
  <div id="welcomeOverlay" class="welcome-overlay" aria-hidden="true">
    <div class="welcome-card">
      <h2>Welcome to Green Sense</h2>
      <p>Let's have healthy coffee.</p>
    </div>
  </div>
<?php endif; ?>

<header>
  <div class="container header-wrap">
    <div class="logo">Green Sense</div>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="gallery.php">Gallery</a></li>
        <li><a href="products.php">Products</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="cart.php">Cart</a></li>
        <?php if ($isLoggedIn): ?>
          <li><a href="profile.php">Profile</a></li>
          <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
          <li><a href="auth.php">Login/Register</a></li>
        <?php endif; ?>
      </ul>
    </nav>
    <div class="header-right">
      <div class="user-chip">Hi, <?php echo htmlspecialchars($displayName); ?></div>
      <a class="cart-chip" href="cart.php">Cart: <span><?php echo (int) $cartCount; ?></span></a>
    </div>
  </div>
</header>
<?php if ($isHomePage): ?>
  <script>
    (function () {
      const overlay = document.getElementById('welcomeOverlay');
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
