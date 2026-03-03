<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gallery - Green Sense</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;700&family=Manrope:wght@400;500;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
  <?php include __DIR__ . '/partials/header.php'; ?>

  <main class="container">
    <section class="fade-in visible gallery-section">
      <h2> Gallery </h2>
      <p class="section-note">Coffee estate and tea estate moments from farming, extraction, and visitors.</p>

      <div class="gallery-grid">
        <article class="gallery-card">
          <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1200&q=80" alt="Coffee estate field view" />
          <h3>Coffee Estate Landscape</h3>
        </article>

        <article class="gallery-card">
          <img src="https://images.unsplash.com/photo-1597481499750-3e6b22637e12?auto=format&fit=crop&w=1200&q=80" alt="Tea estate leaves and plantation" />
          <h3>Tea Estate Plantation</h3>
        </article>

        <article class="gallery-card">
          <img src="https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=1200&q=80" alt="Coffee seeds being processed" />
          <h3>Coffee Seed Extraction Process</h3>
        </article>

        <article class="gallery-card">
          <img src="https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=1200&q=80" alt="Tea leaves collected for processing" />
          <h3>Tea Leaf Collection & Processing</h3>
        </article>

        <article class="gallery-card">
          <img src="https://images.unsplash.com/photo-1494314671902-399b18174975?auto=format&fit=crop&w=1200&q=80" alt="Visitors tasting estate coffee" />
          <h3>Visitors Buying Fresh Coffee</h3>
        </article>

        <article class="gallery-card">
          <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1200&q=80" alt="People visiting coffee and tea estate shop" />
          <h3>People Visiting Estate Store</h3>
        </article>
      </div>
    </section>
  </main>

  <?php include __DIR__ . '/partials/footer.php'; ?>
  <?php include __DIR__ . '/chatbot.php'; ?>
</body>
</html>
