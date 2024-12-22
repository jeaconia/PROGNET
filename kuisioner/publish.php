<?php
session_start();
include '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login-admin/login.php");
    exit();
}

$pertanyaan_id = $_GET['id'] ?? 0;

$sql = "UPDATE pertanyaan SET is_published = 1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pertanyaan_id);

if ($stmt->execute()) {
    header("Location: detail.php?id=$pertanyaan_id&status=published");
} else {
    echo "Gagal mem-publish pertanyaan.";
}
?>
