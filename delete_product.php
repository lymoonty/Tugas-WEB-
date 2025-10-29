<?php
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: produk.php');
    exit();
}

$pdo = getDBConnection();
$product_id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT id FROM products WHERE id = ?");
$stmt->execute([$product_id]);
if (!$stmt->fetch()) {
    header('Location: produk.php?msg=not_found');
    exit();
}

try {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    header('Location: produk.php?msg=deleted');
    exit();
} catch (PDOException $e) {
    error_log("Delete Product Error: " . $e->getMessage());
    die('Terjadi kesalahan saat menghapus produk.');
}
?>