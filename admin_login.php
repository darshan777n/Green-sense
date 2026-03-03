<?php
session_start();
require_once __DIR__ . '/db.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: admin_dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT id, username, password_hash FROM admins WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header('Location: admin_dashboard.php');
        exit;
    }
    $error = 'Invalid admin username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login - Green Sense</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;700&family=Manrope:wght@400;500;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
  <?php include __DIR__ . '/partials/header.php'; ?>

  <main class="container auth-main">
    <section class="auth-section fade-in visible">
      <h2>Admin Login</h2>
      <p class="section-note">Only admin can access enquiry submissions.</p>
      <?php if ($error): ?>
        <p class="auth-msg auth-error"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>
      <form class="auth-form" method="post">
        <label for="admin-username">Username</label>
        <input id="admin-username" name="username" type="text" required />
        <label for="admin-password">Password</label>
        <input id="admin-password" name="password" type="password" required />
        <button class="btn btn-secondary auth-btn" type="submit">Login</button>
      </form>
      <p class="section-note">Default: username `admin`, password `Admin@123`</p>
    </section>
  </main>

  <?php include __DIR__ . '/partials/footer.php'; ?>
  <?php include __DIR__ . '/chatbot.php'; ?>
</body>
</html>
