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

    // Hapus data mahasiswa berdasarkan ID
    $sql = "DELETE FROM mahasiswa WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Mahasiswa berhasil dihapus!'); window.location.href='manage-mhs.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menghapus mahasiswa.'); window.location.href='manage-mhs.php';</script>";
    }

    $stmt->close();
} else {
    header("Location: manage-mhs.php");
    exit();
}
?>
