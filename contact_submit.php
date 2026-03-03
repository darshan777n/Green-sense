<?php
session_start();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php#contact-us');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $message === '') {
    $_SESSION['contact_error'] = 'Please fill all contact form fields.';
    header('Location: index.php#contact-us');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['contact_error'] = 'Please enter a valid email address.';
    header('Location: index.php#contact-us');
    exit;
}

try {
    $stmt = $pdo->prepare('INSERT INTO enquiries (name, email, message) VALUES (?, ?, ?)');
    $stmt->execute([$name, $email, $message]);
} catch (PDOException $e) {
    $_SESSION['contact_error'] = 'Unable to save enquiry right now. Please try again.';
    header('Location: index.php#contact-us');
    exit;
}

$_SESSION['contact_success'] = 'Thanks for your enquiry. Our team will contact you soon.';
header('Location: index.php#contact-us');
exit;
?>
