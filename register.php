<?php
session_start();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: auth.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($name === '' || $email === '' || $password === '') {
    $_SESSION['auth_error'] = 'All registration fields are required.';
    header('Location: auth.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['auth_error'] = 'Please enter a valid email address.';
    header('Location: auth.php');
    exit;
}

if (strlen($password) < 6) {
    $_SESSION['auth_error'] = 'Password must be at least 6 characters.';
    header('Location: auth.php');
    exit;
}

$checkStmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$checkStmt->execute([$email]);

if ($checkStmt->fetch()) {
    $_SESSION['auth_error'] = 'Email is already registered. Please login.';
    header('Location: auth.php');
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$insertStmt = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
$insertStmt->execute([$name, $email, $hash]);

$_SESSION['auth_success'] = 'Registration successful. You can login now.';
header('Location: auth.php');
exit;
?>
