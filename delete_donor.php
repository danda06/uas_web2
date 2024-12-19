<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM donors WHERE id = ?");
    if ($stmt->execute([$id])) {
        $_SESSION['success'] = "Pendonor berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus pendonor!";
    }
    header("Location: admin.php");
    exit();
}
?>