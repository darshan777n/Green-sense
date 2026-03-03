<?php
declare(strict_types=1);

$host = '127.0.0.1';
$port = 3306;
$db = 'brewleaf';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    // Connect to MySQL server first so we can auto-create database/table on first run.
    $serverDsn = "mysql:host=$host;port=$port;charset=$charset";
    $pdo = new PDO($serverDsn, $user, $pass, $options);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$db`");
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(80) NOT NULL,
            email VARCHAR(120) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB"
    );
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS enquiries (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(80) NOT NULL,
            email VARCHAR(120) NOT NULL,
            message TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB"
    );
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS admins (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(60) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB"
    );
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS wishlist (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            product_id VARCHAR(60) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_user_product (user_id, product_id),
            CONSTRAINT fk_wishlist_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB"
    );
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            payment_method VARCHAR(20) NOT NULL,
            total_amount DECIMAL(10,2) NOT NULL,
            status VARCHAR(20) NOT NULL DEFAULT 'paid',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB"
    );
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS order_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            product_id VARCHAR(60) NOT NULL,
            product_name VARCHAR(180) NOT NULL,
            unit_price DECIMAL(10,2) NOT NULL,
            quantity INT NOT NULL,
            line_total DECIMAL(10,2) NOT NULL,
            CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
        ) ENGINE=InnoDB"
    );
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS site_visits (
            id INT AUTO_INCREMENT PRIMARY KEY,
            session_id VARCHAR(128) NOT NULL UNIQUE,
            first_seen TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            last_seen TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            visit_count INT NOT NULL DEFAULT 1
        ) ENGINE=InnoDB"
    );
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS reviews (
            id INT AUTO_INCREMENT PRIMARY KEY,
            reviewer_name VARCHAR(80) NOT NULL,
            rating TINYINT UNSIGNED NOT NULL,
            review_text TEXT NOT NULL,
            is_approved TINYINT(1) NOT NULL DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_reviews_approved_created (is_approved, created_at)
        ) ENGINE=InnoDB"
    );

    $adminCount = (int) $pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn();
    if ($adminCount === 0) {
        $defaultUser = 'admin';
        $defaultPassHash = password_hash('Admin@123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO admins (username, password_hash) VALUES (?, ?)');
        $stmt->execute([$defaultUser, $defaultPassHash]);
    }

    $reviewCount = (int) $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
    if ($reviewCount === 0) {
        $seedReviews = $pdo->prepare(
            'INSERT INTO reviews (reviewer_name, rating, review_text, is_approved) VALUES (?, ?, ?, 1)'
        );
        $seedReviews->execute(['Priya M.', 5, 'The Signature Arabica is now my morning go-to. Fresh aroma, smooth taste, and very consistent quality.']);
        $seedReviews->execute(['Rohit S.', 4, 'Classic Masala Chai has the perfect spice balance. Tastes authentic and comforting every time.']);
        $seedReviews->execute(['Ananya K.', 5, 'Fast delivery and excellent packaging. Green Harmony Tea is light, clean, and great for evening relaxation.']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    die('Database connection failed in ' . __FILE__ . ' :: ' . $e->getMessage());
}
?>
