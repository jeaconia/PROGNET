<?php
session_start();
include '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login-admin/login.php");
    exit();
}

$id = $_GET['id'];

// Hapus jawaban yang terkait dengan pertanyaan terlebih dahulu
$sql = "DELETE FROM jawaban WHERE pertanyaan_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // Setelah itu, hapus pertanyaan
    $sql = "DELETE FROM pertanyaan WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: read-pertanyaan.php");
        exit();
    } else {
        echo "Error deleting pertanyaan: " . $stmt->error;
    }
} else {
    echo "Error deleting jawaban: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>