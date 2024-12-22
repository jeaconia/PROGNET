<?php
session_start();
include '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login-admin/login.php");
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    // Gunakan try-catch untuk menangani kesalahan
    try {
        // Mulai transaksi
        $conn->begin_transaction();

        // Hapus jawaban yang terkait dengan pertanyaan
        $sql_jawaban = "DELETE FROM jawaban WHERE pertanyaan_id = ?";
        $stmt_jawaban = $conn->prepare($sql_jawaban);
        if (!$stmt_jawaban) {
            throw new Exception("Prepare failed for jawaban: " . $conn->error);
        }
        $stmt_jawaban->bind_param("i", $id);
        if (!$stmt_jawaban->execute()) {
            throw new Exception("Execute failed for jawaban: " . $stmt_jawaban->error);
        }
        $stmt_jawaban->close();

        // Hapus pilihan yang terkait dengan pertanyaan
        $sql_pilihan = "DELETE FROM pilihan WHERE pertanyaan_id = ?";
        $stmt_pilihan = $conn->prepare($sql_pilihan);
        if (!$stmt_pilihan) {
            throw new Exception("Prepare failed for pilihan: " . $conn->error);
        }
        $stmt_pilihan->bind_param("i", $id);
        if (!$stmt_pilihan->execute()) {
            throw new Exception("Execute failed for pilihan: " . $stmt_pilihan->error);
        }
        $stmt_pilihan->close();

        // Hapus pertanyaan
        $sql_pertanyaan = "DELETE FROM pertanyaan WHERE id = ?";
        $stmt_pertanyaan = $conn->prepare($sql_pertanyaan);
        if (!$stmt_pertanyaan) {
            throw new Exception("Prepare failed for pertanyaan: " . $conn->error);
        }
        $stmt_pertanyaan->bind_param("i", $id);
        if (!$stmt_pertanyaan->execute()) {
            throw new Exception("Execute failed for pertanyaan: " . $stmt_pertanyaan->error);
        }
        $stmt_pertanyaan->close();

        // Komit transaksi
        $conn->commit();

        // Redirect setelah berhasil menghapus
        header("Location: view-pertanyaan.php");
        exit();
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
} else {
    // Redirect jika ID tidak valid
    header("Location: view-pertanyaan.php");
    exit();
}
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
