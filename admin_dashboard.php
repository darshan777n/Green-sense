<?php
session_start();
require_once __DIR__ . '/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

$adminName = $_SESSION['admin_username'] ?? 'admin';

$userCount = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$orderCount = (int) $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$enquiryCount = (int) $pdo->query('SELECT COUNT(*) FROM enquiries')->fetchColumn();
$uniqueVisitors = (int) $pdo->query('SELECT COUNT(*) FROM site_visits')->fetchColumn();
$totalVisits = (int) $pdo->query('SELECT COALESCE(SUM(visit_count),0) FROM site_visits')->fetchColumn();

$recentOrders = $pdo->query(
    'SELECT o.id, u.name, u.email, o.payment_method, o.total_amount, o.status, o.created_at
     FROM orders o
     JOIN users u ON u.id = o.user_id
     ORDER BY o.id DESC
     LIMIT 10'
)->fetchAll();

$recentEnquiries = $pdo->query(
    'SELECT id, name, email, message, created_at
     FROM enquiries
     ORDER BY id DESC
     LIMIT 20'
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard - Green Sense</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;700&family=Manrope:wght@400;500;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
  <?php include __DIR__ . '/partials/header.php'; ?>

  <main class="container">
    <section class="fade-in visible">
      <h2>Admin Dashboard</h2>
      <p class="section-note">Admin: <?php echo htmlspecialchars($adminName); ?> | <a href="admin_logout.php">Admin Logout</a></p>

      <div class="admin-stats-grid">
        <article class="admin-stat-card">
          <h3>Registered Users</h3>
          <p><?php echo $userCount; ?></p>
        </article>
        <article class="admin-stat-card">
          <h3>Total Orders</h3>
          <p><?php echo $orderCount; ?></p>
        </article>
        <article class="admin-stat-card">
          <h3>Total Enquiries</h3>
          <p><?php echo $enquiryCount; ?></p>
        </article>
        <article class="admin-stat-card">
          <h3>Unique Visitors</h3>
          <p><?php echo $uniqueVisitors; ?></p>
        </article>
        <article class="admin-stat-card">
          <h3>Total Page Visits</h3>
          <p><?php echo $totalVisits; ?></p>
        </article>
      </div>
    </section>

    <section class="fade-in visible">
      <h2>Recent Orders</h2>
      <?php if (!$recentOrders): ?>
        <p class="section-note">No orders yet.</p>
      <?php else: ?>
        <div class="enquiry-table-wrap">
          <table class="enquiry-table">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Email</th>
                <th>Payment</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recentOrders as $order): ?>
                <tr>
                  <td>#<?php echo (int) $order['id']; ?></td>
                  <td><?php echo htmlspecialchars($order['name']); ?></td>
                  <td><?php echo htmlspecialchars($order['email']); ?></td>
                  <td><?php echo htmlspecialchars(strtoupper($order['payment_method'])); ?></td>
                  <td>$<?php echo number_format((float) $order['total_amount'], 2); ?></td>
                  <td><?php echo htmlspecialchars(ucfirst($order['status'])); ?></td>
                  <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </section>

    <section class="fade-in visible">
      <h2>Recent Enquiries</h2>
      <?php if (!$recentEnquiries): ?>
        <p class="section-note">No enquiries received yet.</p>
      <?php else: ?>
        <div class="enquiry-table-wrap">
          <table class="enquiry-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Submitted</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recentEnquiries as $row): ?>
                <tr>
                  <td><?php echo (int) $row['id']; ?></td>
                  <td><?php echo htmlspecialchars($row['name']); ?></td>
                  <td><a href="mailto:<?php echo htmlspecialchars($row['email']); ?>"><?php echo htmlspecialchars($row['email']); ?></a></td>
                  <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                  <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </section>
  </main>

  <?php include __DIR__ . '/partials/footer.php'; ?>
  <?php include __DIR__ . '/chatbot.php'; ?>
</body>
</html>
