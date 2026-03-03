<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us - Green Sense</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;700&family=Manrope:wght@400;500;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
  <?php include __DIR__ . '/partials/header.php'; ?>

  <main class="container">
    <section class="fade-in visible contact-location">
      <h2>Contact & Location</h2>
      <p class="section-note">Reach us for enquiries, wholesale orders, and support.</p>
      <div class="location-card">
        <h3>Green Sense Store</h3>
        <p><strong>Location:</strong> Madikeri, Karnataka, India</p>
        <p><strong>Phone:</strong> +91 98765 43210</p>
        <p><strong>Email:</strong> support@Green Sense.com</p>
        <p><strong>Working Hours:</strong> Mon - Sat, 9:00 AM to 7:00 PM</p>
        <a class="btn btn-detail btn-inline" target="_blank" rel="noopener noreferrer" href="https://www.google.com/maps/search/?api=1&query=Madikeri%2C+Karnataka">Open in Google Maps</a>
      </div>
    </section>
  </main>

  <?php include __DIR__ . '/partials/footer.php'; ?>
  <?php include __DIR__ . '/chatbot.php'; ?>
</body>
</html>
