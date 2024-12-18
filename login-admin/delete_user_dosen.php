<?php
session_start();
include '../config.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus data dosen berdasarkan ID
    $sql = "DELETE FROM dosen WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Dosen berhasil dihapus!'); window.location.href='manage-dosen.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menghapus dosen.'); window.location.href='manage-dosen.php';</script>";
    }

    $stmt->close();
} else {
    header("Location: manage-dosen.php");
    exit();
}
?>