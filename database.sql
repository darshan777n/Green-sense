CREATE DATABASE IF NOT EXISTS brewleaf;
USE brewleaf;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reviewer_name VARCHAR(80) NOT NULL,
  rating TINYINT UNSIGNED NOT NULL,
  review_text TEXT NOT NULL,
  is_approved TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_reviews_approved_created (is_approved, created_at)
);

INSERT INTO reviews (reviewer_name, rating, review_text, is_approved)
SELECT 'Priya M.', 5, 'The Signature Arabica is now my morning go-to. Fresh aroma, smooth taste, and very consistent quality.', 1
WHERE NOT EXISTS (SELECT 1 FROM reviews);
