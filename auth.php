<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = $_SESSION['auth_error'] ?? '';
$success = $_SESSION['auth_success'] ?? '';
unset($_SESSION['auth_error'], $_SESSION['auth_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login / Register - Green Sense</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;700&family=Manrope:wght@400;500;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
  <?php include __DIR__ . '/partials/header.php'; ?>

  <main class="container auth-main">
    <section class="auth-section fade-in visible">
      <h2>Login or Create Account</h2>
      <p class="section-note">Store users securely in your local XAMPP MySQL database.</p>

      <?php if ($error): ?>
        <p class="auth-msg auth-error"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>

      <?php if ($success): ?>
        <p class="auth-msg auth-success"><?php echo htmlspecialchars($success); ?></p>
      <?php endif; ?>

      <div class="auth-grid">
        <form class="auth-form" action="login.php" method="post">
          <h3>Login</h3>
          <label for="login-email">Email</label>
          <input id="login-email" name="email" type="email" required />

          <label for="login-password">Password</label>
          <input id="login-password" name="password" type="password" required />

          <button class="btn btn-secondary auth-btn" type="submit">Login</button>
        </form>

        <form class="auth-form" action="register.php" method="post">
          <h3>Register</h3>
          <label for="register-name">Full Name</label>
          <input id="register-name" name="name" type="text" minlength="2" maxlength="80" required />

          <label for="register-email">Email</label>
          <input id="register-email" name="email" type="email" required />

          <label for="register-password">Password</label>
          <input id="register-password" name="password" type="password" minlength="6" required />

          <button class="btn btn-secondary auth-btn" type="submit">Create Account</button>
        </form>
      </div>
    </section>
  </main>

  <?php include __DIR__ . '/partials/footer.php'; ?>
  <?php include __DIR__ . '/chatbot.php'; ?>
</body>
</html>
