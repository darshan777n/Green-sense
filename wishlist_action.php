<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/products_data.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['auth_error'] = 'Please login to use wishlist.';
    header('Location: auth.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: products.php');
    exit;
}

$products = getProducts();
$userId = (int) $_SESSION['user_id'];
$productId = trim($_POST['product_id'] ?? '');
$action = trim($_POST['action'] ?? 'add');
$returnTo = $_POST['return_to'] ?? 'products.php';

if (!in_array($returnTo, ['products.php', 'profile.php'], true) && strpos($returnTo, 'product_detail.php?id=') !== 0) {
    $returnTo = 'products.php';
}

if (!isset($products[$productId])) {
    $_SESSION['flash_message'] = 'Invalid product selected.';
    header('Location: ' . $returnTo);
    exit;
}

if ($action === 'add') {
    $stmt = $pdo->prepare('INSERT IGNORE INTO wishlist (user_id, product_id) VALUES (?, ?)');
    $stmt->execute([$userId, $productId]);
    $_SESSION['flash_message'] = $products[$productId]['name'] . ' added to wishlist.';
    header('Location: ' . $returnTo);
    exit;
}

if ($action === 'remove') {
    $stmt = $pdo->prepare('DELETE FROM wishlist WHERE user_id = ? AND product_id = ?');
    $stmt->execute([$userId, $productId]);
    $_SESSION['flash_message'] = $products[$productId]['name'] . ' removed from wishlist.';
    header('Location: ' . $returnTo);
    exit;
}

$_SESSION['flash_message'] = 'Invalid wishlist action.';
header('Location: ' . $returnTo);
exit;
?>
