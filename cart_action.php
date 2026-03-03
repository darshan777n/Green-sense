<?php
session_start();
require_once __DIR__ . '/products_data.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: products.php');
    exit;
}

$products = getProducts();
$productId = $_POST['product_id'] ?? '';
$action = $_POST['action'] ?? '';
$returnTo = $_POST['return_to'] ?? 'products.php';
$quantity = (int) ($_POST['quantity'] ?? 1);
$quantity = max(1, min(20, $quantity));

if (!in_array($returnTo, ['index.php', 'products.php', 'cart.php'], true) && strpos($returnTo, 'product_detail.php?id=') !== 0) {
    $returnTo = 'products.php';
}

if (!isset($products[$productId])) {
    $_SESSION['flash_message'] = 'Invalid product selected.';
    header('Location: ' . $returnTo);
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (in_array($action, ['add', 'buy'], true) && !isset($_SESSION['user_id'])) {
    $_SESSION['auth_error'] = 'Please login to add products to cart or buy now.';
    header('Location: auth.php');
    exit;
}

if ($action === 'add') {
    if (!isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = 0;
    }
    $_SESSION['cart'][$productId] += $quantity;
    $_SESSION['flash_message'] = $quantity . ' x ' . $products[$productId]['name'] . ' added to cart.';
    header('Location: ' . $returnTo);
    exit;
}

if ($action === 'buy') {
    $_SESSION['buy_now_product_id'] = $productId;
    $_SESSION['buy_now_quantity'] = $quantity;
    header('Location: payment.php');
    exit;
}

if ($action === 'remove') {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
    $_SESSION['flash_message'] = $products[$productId]['name'] . ' removed from cart.';
    header('Location: ' . $returnTo);
    exit;
}

$_SESSION['flash_message'] = 'Invalid action.';
header('Location: ' . $returnTo);
exit;
?>
