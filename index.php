<?php
session_start();
require_once __DIR__ . '/db.php';

$flash = $_SESSION['flash_message'] ?? '';
$contactSuccess = $_SESSION['contact_success'] ?? '';
$contactError = $_SESSION['contact_error'] ?? '';
unset($_SESSION['flash_message']);
unset($_SESSION['contact_success'], $_SESSION['contact_error']);

$reviews = $pdo->query(
    'SELECT reviewer_name, rating, review_text
     FROM reviews
     WHERE is_approved = 1
     ORDER BY created_at DESC
     LIMIT 6'
)->fetchAll();

if (!$reviews) {
    $reviews = [
        [
            'reviewer_name' => 'Priya M.',
            'rating' => 5,
            'review_text' => 'The Signature Arabica is now my morning go-to. Fresh aroma, smooth taste, and very consistent quality.',
        ],
        [
            'reviewer_name' => 'Rohit S.',
            'rating' => 4,
            'review_text' => 'Classic Masala Chai has the perfect spice balance. Tastes authentic and comforting every time.',
        ],
        [
            'reviewer_name' => 'Ananya K.',
            'rating' => 5,
            'review_text' => 'Fast delivery and excellent packaging. Green Harmony Tea is light, clean, and great for evening relaxation.',
        ],
    ];
}

function review_stars(int $rating): string
{
    $safeRating = max(1, min(5, $rating));
    return str_repeat('&#9733;', $safeRating) . str_repeat('&#9734;', 5 - $safeRating);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Green Sense - Coffee & Tea Products</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;700&family=Manrope:wght@400;500;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
  <?php include __DIR__ . '/partials/header.php'; ?>

  <main class="container" id="home">
    <section class="banner fade-in">
      <div class="banner-content">
        <h1>Brewed Energy, Steeped Calm</h1>
        <p>From small-batch coffee roasts to premium loose-leaf teas, your perfect cup starts here.</p>
        <a href="products.php"><button class="btn btn-primary">Explore Products</button></a>
      </div>
    </section>

    <section class="about fade-in" id="about">
      <h2>About Our Company</h2>
      <div class="about-wrap">
        <p>
          Green Sense blends tradition with modern taste. We partner with growers, choose high-grade beans and leaves,
          and craft products for people who love quality. Every coffee and tea is selected for flavor, freshness,
          and consistency so each cup feels special.
        </p>
        <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=900&q=80" alt="Coffee and tea cups on a wooden table" />
      </div>
    </section>

    <section class="fade-in" id="products">
      <h2>Product Information</h2>
      <p class="section-note">Use filters to browse coffee and tea collections.</p>
      <?php if ($flash): ?>
        <p class="auth-msg auth-success"><?php echo htmlspecialchars($flash); ?></p>
      <?php endif; ?>

      <div class="filters">
        <button class="filter-btn active" data-filter="all">All</button>
        <button class="filter-btn" data-filter="coffee">Coffee</button>
        <button class="filter-btn" data-filter="tea">Tea</button>
      </div>

      <div class="products-grid" id="productsGrid">
        <article class="product-card" data-category="coffee">
          <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=900&q=80" alt="Signature Arabica Coffee" />
          <div class="product-content">
            <h3>Signature Arabica Coffee</h3>
            <p>Medium roast with cocoa notes and smooth finish, ideal for pour-over and French press.</p>
            <div class="price-row">
              <div class="price">$14.99</div>
              <small>250g</small>
            </div>
            <form action="cart_action.php" method="post">
              <input type="hidden" name="product_id" value="arabica" />
              <input type="hidden" name="quantity" value="1" />
              <input type="hidden" name="return_to" value="index.php" />
              <button class="btn btn-secondary add-cart" type="submit" name="action" value="add">Add to Cart</button>
            </form>
            <a class="product-detail-link" href="product_detail.php?id=arabica">View Details</a>
          </div>
        </article>

        <article class="product-card" data-category="tea">
          <img src="https://images.unsplash.com/photo-1597481499750-3e6b22637e12?auto=format&fit=crop&w=900&q=80" alt="Classic Masala Chai" />
          <div class="product-content">
            <h3>Classic Masala Chai</h3>
            <p>Black tea with cardamom, cinnamon, and ginger for a comforting, spiced everyday cup.</p>
            <div class="price-row">
              <div class="price">$10.99</div>
              <small>200g</small>
            </div>
            <form action="cart_action.php" method="post">
              <input type="hidden" name="product_id" value="masala-chai" />
              <input type="hidden" name="quantity" value="1" />
              <input type="hidden" name="return_to" value="index.php" />
              <button class="btn btn-secondary add-cart" type="submit" name="action" value="add">Add to Cart</button>
            </form>
            <a class="product-detail-link" href="product_detail.php?id=masala-chai">View Details</a>
          </div>
        </article>

        <article class="product-card" data-category="tea">
          <img src="https://images.unsplash.com/photo-1597318181409-cf64d0b5d8a2?auto=format&fit=crop&w=900&q=80" alt="Green Harmony Tea" />
          <div class="product-content">
            <h3>Green Harmony Tea</h3>
            <p>Light, fresh green tea packed with antioxidants and a crisp, calming flavor profile.</p>
            <div class="price-row">
              <div class="price">$12.49</div>
              <small>180g</small>
            </div>
            <form action="cart_action.php" method="post">
              <input type="hidden" name="product_id" value="green-harmony" />
              <input type="hidden" name="quantity" value="1" />
              <input type="hidden" name="return_to" value="index.php" />
              <button class="btn btn-secondary add-cart" type="submit" name="action" value="add">Add to Cart</button>
            </form>
            <a class="product-detail-link" href="product_detail.php?id=green-harmony">View Details</a>
          </div>
        </article>

        <article class="product-card" data-category="coffee">
          <img src="https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=900&q=80" alt="Dark Roast Espresso Blend" />
          <div class="product-content">
            <h3>Dark Roast Espresso Blend</h3>
            <p>Bold espresso blend with deep caramelized notes for shots, lattes, and cappuccinos.</p>
            <div class="price-row">
              <div class="price">$16.49</div>
              <small>250g</small>
            </div>
            <form action="cart_action.php" method="post">
              <input type="hidden" name="product_id" value="espresso-blend" />
              <input type="hidden" name="quantity" value="1" />
              <input type="hidden" name="return_to" value="index.php" />
              <button class="btn btn-secondary add-cart" type="submit" name="action" value="add">Add to Cart</button>
            </form>
            <a class="product-detail-link" href="product_detail.php?id=espresso-blend">View Details</a>
          </div>
        </article>
      </div>

      <div class="more-products-wrap">
        <a class="btn btn-detail btn-inline" href="products.php">View More Products</a>
      </div>
    </section>

    <section class="fade-in reviews-section" id="reviews">
      <h2>Customer Reviews</h2>
      <p class="section-note">What our customers are saying about Green Sense.</p>
      <div class="reviews-grid">
        <?php foreach ($reviews as $review): ?>
          <?php $rating = max(1, min(5, (int) $review['rating'])); ?>
          <article class="review-card">
            <div class="review-head">
              <h3><?php echo htmlspecialchars($review['reviewer_name']); ?></h3>
              <span class="review-stars" aria-label="<?php echo $rating; ?> out of 5 stars">
                <?php echo review_stars($rating); ?>
              </span>
            </div>
            <p>"<?php echo nl2br(htmlspecialchars($review['review_text'])); ?>"</p>
          </article>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="fade-in contact-section" id="contact-us">
      <h2>Contact Us</h2>
      <p class="section-note">Send your enquiry and we will get back to you.</p>
      <?php if ($contactSuccess): ?>
        <p class="auth-msg auth-success"><?php echo htmlspecialchars($contactSuccess); ?></p>
      <?php endif; ?>
      <?php if ($contactError): ?>
        <p class="auth-msg auth-error"><?php echo htmlspecialchars($contactError); ?></p>
      <?php endif; ?>
      <div class="contact-grid single-col">
        <form class="contact-form" action="contact_submit.php" method="post">
          <label for="contact-name">Name</label>
          <input id="contact-name" name="name" type="text" maxlength="80" required />

          <label for="contact-email">Email</label>
          <input id="contact-email" name="email" type="email" required />

          <label for="contact-message">Enquiry</label>
          <textarea id="contact-message" name="message" rows="5" maxlength="1000" required></textarea>

          <button class="btn btn-secondary" type="submit">Send Enquiry</button>
        </form>
      </div>
    </section>
  </main>

  <?php include __DIR__ . '/partials/footer.php'; ?>

  <script>
    const filterButtons = document.querySelectorAll('.filter-btn');
    const productCards = document.querySelectorAll('.product-card');
    const fadeSections = document.querySelectorAll('.fade-in');

    filterButtons.forEach((button) => {
      button.addEventListener('click', () => {
        filterButtons.forEach((btn) => btn.classList.remove('active'));
        button.classList.add('active');

        const selected = button.dataset.filter;
        productCards.forEach((card) => {
          const isMatch = selected === 'all' || card.dataset.category === selected;
          card.classList.toggle('hidden', !isMatch);
        });
      });
    });

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
          }
        });
      },
      { threshold: 0.12 }
    );

    fadeSections.forEach((section) => observer.observe(section));
  </script>
  <?php include __DIR__ . '/chatbot.php'; ?>
</body>
</html>
